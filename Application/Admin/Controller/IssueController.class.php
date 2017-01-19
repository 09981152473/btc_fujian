<?php
//dezend by http://www.yunlu99.com/ QQ:270656184
namespace Admin\Controller;

class IssueController extends AdminController
{
	public function index($name = NULL, $field = NULL, $status = NULL)
	{
		$where = array();
		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else if ($field == 'name') {
				$where['name'] = array('like', '%' . $name . '%');
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($status) {
			$where['status'] = $status - 1;
		}
                
                
                $add_conf = M('add_conf') ->where(array('id'=>1)) ->find();
                $this ->assign('issue_mum',$add_conf['issue_total_mum']);//显示已经设置好的总发行量
                $this ->assign('nextrate',$add_conf['nextrate']);//显示下级提成率
                $this ->assign('jtbl',$add_conf['jtbl']);//显示静态倍率 
                
                $this ->assign('trade',$add_conf['rate_trade']);//交易份额
                $this ->assign('market',$add_conf['rate_market']);//商城份额
                $this ->assign('found',$add_conf['rate_found']);//基金份额
                
                $this ->assign('chabilv',$add_conf['chabilv']);//产比率
          
                //显示所有的发布量
                $issued_num =  M('issue') ->sum('num');
                $this -> assign('issued_num',$issued_num);

		$count = M('Issue')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Issue')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function edit()
	{
		if (empty($_GET['id'])) {
			$this->data = false;
		}
		else {
			$this->data = M('Issue')->where(array('id' => trim($_GET['id'])))->find();
		}

		$this->display();
	}
        
        public function test(){

        }
        

	public function save()
	{
            
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

                //数量控制(总发行量10亿台)
                $num = trim($_POST['num']);
                $all_num = M('issue') -> sum('num');
                
                $issue_num = M('add_conf') ->where(array('id'=>1)) ->field(array('issue_total_mum')) ->find();
                $left_num = $issue_num['issue_total_mum'] - $all_num;
                if($_POST['num'] > $left_num){
                    $this ->error('你还可以发行'.$left_num.'台');
                }
                    
		$_POST['addtime'] = time();
               
		if (strtotime($_POST['time']) != strtotime(addtime(strtotime($_POST['time'])))) {
			$this->error('开启时间格式错误！');
		}

		if ($_POST['id']) {
			$rs = M('Issue')->save($_POST);
		}else {
                
                        //名称重复提示(为后面的做提示用)
                        $name = trim($_POST['name']);
                        $issue_names = M('issue') ->field(array('name')) -> select();
                        $names = array();
                        foreach($issue_names as $k=>$v){
                            if($name==$v['name']){
                                $this ->error('名称不能重复!');
                            }
                        }
                    
			$rs = M('Issue')->add($_POST);
		}

		if ($rs) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function status()
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (IS_POST) {
			$id = array();
			$id = implode(',', $_POST['id']);
		}
		else {
			$id = $_GET['id'];
		}

		if (empty($id)) {
			$this->error('请选择要操作的数据!');
		}

		$where['id'] = array('in', $id);
		$method = $_GET['method'];

		switch (strtolower($method)) {
		case 'forbid':
			$data = array('status' => 0);
			break;

		case 'resume':
			$data = array('status' => 1);
			break;

		case 'delete':
			if (M('Issue')->where($where)->delete()) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}

			break;

		default:
			$this->error('参数非法');
		}

		if (M('Issue')->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function log($name = NULL)
	{
		if ($name && check($name, 'username')) {
			$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
		}
		else {
			$where = array();
		}

		$count = M('IssueLog')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('IssueLog')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function checkAuth()
	{
		if ((S('CLOUDTIME') + (60 * 60)) < time()) {
			S('CLOUD', null);
			S('CLOUD_IP', null);
			S('CLOUD_HOME', null);
			S('CLOUD_DAOQI', null);
			S('CLOUD_GAME', null);
			S('CLOUDTIME', time());
		}

		$CLOUD = S('CLOUD');
		$CLOUD_IP = S('CLOUD_IP');
		$CLOUD_HOME = S('CLOUD_HOME');
		$CLOUD_DAOQI = S('CLOUD_DAOQI');
		$CLOUD_GAME = S('CLOUD_GAME');

		if (!$CLOUD) {
			foreach (C('__CLOUD__') as $k => $v) {
				if (getUrl($v . '/Auth/text') == 1) {
					$CLOUD = $v;
					break;
				}
			}

			if (!$CLOUD) {
				S('CLOUDTIME', time() - (60 * 60 * 24));
				echo '<a title="授权服务器连失败"></a>';
				exit();
			}
			else {
				S('CLOUD', $CLOUD);
			}
		}

		if (!$CLOUD_DAOQI) {
			$CLOUD_DAOQI = getUrl($CLOUD . '/Auth/daoqi?mscode=' . MSCODE);

			if ($CLOUD_DAOQI) {
				S('CLOUD_DAOQI', $CLOUD_DAOQI);
			}
			else {
				S('CLOUDTIME', time() - (60 * 60 * 24));
				echo '<a title="获取授权到期时间失败"></a>';
				exit();
			}
		}

		if (strtotime($CLOUD_DAOQI) < time()) {
			S('CLOUDTIME', time() - (60 * 60 * 24));
			echo '<a title="授权已到期"></a>';
			exit();
		}

		if (!$CLOUD_IP) {
			$CLOUD_IP = getUrl($CLOUD . '/Auth/ip?mscode=' . MSCODE);

			if (!$CLOUD_IP) {
				S('CLOUD_IP', 1);
			}
			else {
				S('CLOUD_IP', $CLOUD_IP);
			}
		}

		if ($CLOUD_IP && ($CLOUD_IP != 1)) {
			$ip_arr = explode('|', $CLOUD_IP);

			if ('/' == DIRECTORY_SEPARATOR) {
				$ip_a = $_SERVER['SERVER_ADDR'];
			}
			else {
				$ip_a = @gethostbyname($_SERVER['SERVER_NAME']);
			}

			if (!$ip_a) {
				S('CLOUDTIME', time() - (60 * 60 * 24));
				echo '<a title="获取本地ip失败"></a>';
				exit();
			}

			if (!in_array($ip_a, $ip_arr)) {
				S('CLOUDTIME', time() - (60 * 60 * 24));
				echo '<a title="匹配授权ip失败"></a>';
				exit();
			}
		}

		if (!$CLOUD_HOME) {
			$CLOUD_HOME = getUrl($CLOUD . '/Auth/home?mscode=' . MSCODE);

			if (!$CLOUD_HOME) {
				S('CLOUD_HOME', 1);
			}
			else {
				S('CLOUD_HOME', $CLOUD_HOME);
			}
		}

		if ($CLOUD_HOME && ($CLOUD_HOME != 1)) {
			$home_arr = explode('|', $CLOUD_HOME);
			$home_a = $_SERVER['SERVER_NAME'];

			if (!$home_a) {
				$home_a = $_SERVER['HTTP_HOST'];
			}

			if (!$home_a) {
				S('CLOUDTIME', time() - (60 * 60 * 24));
				echo '<a title="获取本地域名失败"></a>';
				exit();
			}

			if (!in_array($home_a, $home_arr)) {
				S('CLOUDTIME', time() - (60 * 60 * 24));
				echo '<a title="匹配授权域名失败"></a>';
				exit();
			}
		}

		if (!$CLOUD_GAME) {
			$CLOUD_GAME = getUrl($CLOUD . '/Auth/game?mscode=' . MSCODE);

			if (!$CLOUD_GAME) {
				S('CLOUDTIME', time() - (60 * 60 * 24));
				echo '<a title="授权应用不存在"></a>';
				exit();
			}
			else {
				S('CLOUD_GAME', $CLOUD_GAME);
			}
		}

		$game_arr = explode('|', $CLOUD_GAME);

		if (!in_array('issue', $game_arr)) {
			S('CLOUDTIME', time() - (60 * 60 * 2));
			echo '<a title="认购没有授权"></a>';
			exit();
		}
	}
        
        //设置认购总量
        public function save_issue_total(){
            $issue_total = $_POST['issuetotal'];
            
            //已经认购的总量
            $aleady_num = M('issue') -> sum('num');
            if($issuetotal && $issue_total < $aleady_num){
                $this -> error('新设置的认购总数不能小于已经认购的数量!');
            }
            
            $res = M('add_conf') -> where(array('id'=>1)) ->setField('issue_total_num',$issue_total);
            if($res){
                $this ->success('新的认购总量设置成功!');
            }else{
                $this ->error('设置失败!');
            }

        }
        
        //设置下级提成比例
        public function save_nextrate(){
            $nextrate_str = trim($_POST['nextrate']);
            $nextrate_int = intval($nextrate_str);
            
            if(is_integer($nextrate_int)){
                $res = M('add_conf') -> where(array('id'=>1)) ->setField('nextrate',$nextrate_int);
                if($res){
                    $this ->success('下级提成比例设置成功!');
                }else{
                    $this ->error('下级提成比例设置失败!');
                }
            }else{
                $this ->error('输入的数据类型不是整数!');
            } 
        }
        
        //设置产币率
        public function save_chabilv(){
            $chanbilv_str = trim($_POST['chanbilv']);
            $chanbilv_float = floatval($chanbilv_str);
            
            if(is_float($chanbilv_float)){
                $res = M('add_conf') -> where(array('id'=>1)) ->setField('chabilv',$chanbilv_float);
                if($res){
                    $this ->success('产币率设置成功!');
                }else{
                    $this ->error('产币率设置失败!');
                }
            }else{
                $this ->error('输入的数据类型不对!');
            } 
        }
        
        
        //设置静态倍率
        public function save_jtbl(){
            $jtbl_str = trim($_POST['jtbl']);
            $jtbl_float = floatval($jtbl_str);
            
            if(is_float($jtbl_float)){
                $res = M('add_conf') -> where(array('id'=>1)) ->setField('jtbl',$jtbl_float);
                if($res){
                    $this ->success('静态倍率设置成功!');
                }else{
                    $this ->error('静态倍率设置失败!');
                }
            }else{
                $this ->error('输入有误!');
            } 
        }
        
        //设置奖金分配比例
        public function save_fenpei(){
            $trade_str = trim($_POST['trade']);
            $trade_int = intval($trade_str);
            
            $market_str = trim($_POST['market']);
            $market_int = intval($market_str);
            
            $found_str = trim($_POST['found']);
            $found_int = intval($found_str);
            
            if((is_integer($trade_int)) && (is_integer($market_int)) && (is_integer($found_int)) ){
                $total = $trade_int + $market_int + $found_int;
                if($total == 100){
                    
                    $res[] = M('add_conf') -> where(array('id'=>1)) ->setField('rate_trade',$trade_int);
                    $res[] = M('add_conf') -> where(array('id'=>1)) ->setField('rate_market',$market_int);
                    $res[] = M('add_conf') -> where(array('id'=>1)) ->setField('rate_found',$found_int);
                    if($res){
                        $this ->success('奖金分配比例设置成功!');
                    }else{
                        $this ->error('奖金分配比例设置失败!');
                    }
                    
                }else{
                    $this ->error('三者之和的比例必须为100');
                }
            }else{
                $this ->error('输入的数据类型不是整数!');
            } 
            
        }
        
        
        //设置新的全局参数
        public function conf(){

            if(empty($_POST)){ //显示
                
                $add_conf = M('add_conf') ->where(array('id'=>1)) ->find();
                $this ->assign('issue_mum',$add_conf['issue_total_mum']);//显示已经设置好的总发行量
                $this ->assign('nextrate',$add_conf['nextrate']);//显示下级提成率
                $this ->assign('jtbl',$add_conf['jtbl']);//显示静态倍率 
                
                $this ->assign('trade',$add_conf['rate_trade']);//交易份额
                $this ->assign('market',$add_conf['rate_market']);//商城份额
                $this ->assign('found',$add_conf['rate_found']);//基金份额
                
                $this ->assign('chanbilv',$add_conf['chanbilv']);//产比率
          
                //显示所有的发布量
                $issued_num =  M('issue') ->sum('num');
                $this -> assign('issued_num',$issued_num);
                
                $this ->display();
                
            }else{ //编辑
                
                if((!$_POST['issue_total_mum']) || (!$_POST['chanbilv']) || (!$_POST['rate_trade']) || (!$_POST['rate_market']) || (!$_POST['rate_found'])){
                    $this ->error('数据不能为空！');
                }
                
                $issue_total_num_int = intval($_POST['issue_total_mum']);
                if(!is_integer($issue_total_num_int)){
                    $this ->error('最大发行量数据输入有误！');
                }
                
                $chanbilv_float = floatval($_POST['chanbilv']);
                if(!is_float($chanbilv_float)){
                    $this -> error('产品率输入错误！');
                }
                
                $jtbl_float =  floatval($_POST['jtbl']);
                if(!is_float($jtbl_float)){
                    $this -> error('静态倍率输入错误！');
                }
                
                $trade_int = intval($_POST['rate_trade']);
                if(!is_integer($trade_int)){
                    $this ->error('交易份额数据输入有误!');
                }
                
                $market_int = intval($_POST['rate_market']);
                if(!is_integer($market_int)){
                    $this ->error('商场份额数据输入有误！');
                }
                
                $found_int = intval($_POST['rate_found']);
                if(!is_integer($found_int)){
                    $this ->error('基金份额数据输入有误！');
                }
            
                //已经认购的总量
                $aleady_num = M('issue') -> sum('num');
                if($issue_total_num_int && $issue_total_num_int < $aleady_num){
                    $this -> error('新设置的认购总数不能小于已经认购的数量!');
                }
      
                $total = intval($_POST['rate_trade']) + intval($_POST['rate_market']) + intval($_POST['rate_found']);
                if($total == 100){
                    $res = M('add_conf') ->where(array('id'=>1)) ->save($_POST);
                    if($res){
                        $this -> success('新的参数设置成功!');
                    }else{
                        $this -> error('新的参数设置失败!');
                    }
                }else{
                    $this ->error('三者之和的比例必须为100');
                }
                
                
            }
            
        }
        
        
}

?>
