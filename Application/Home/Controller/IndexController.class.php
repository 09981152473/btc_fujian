<?php
//dezend by http://www.yunlu99.com/ QQ:270656184
namespace Home\Controller;

class IndexController extends HomeController
{
	public function index()
	{
		$indexAdver = (APP_DEBUG ? null : S('index_indexAdver'));

		if (!$indexAdver) {
			$indexAdver = M('Adver')->where(array('status' => 1))->order('id asc')->select();
			S('index_indexAdver', $indexAdver);
		}

		$this->assign('indexAdver', $indexAdver);
		$indexArticleType = (APP_DEBUG ? null : S('index_indexArticleType'));

		if (!$indexArticleType) {
			$indexArticleType = M('ArticleType')->where(array('status' => 1, 'index' => 1))->order('sort asc ,id desc')->limit(3)->select();
			S('index_indexArticleType', $indexArticleType);
		}

		$this->assign('indexArticleType', $indexArticleType);
		$indexArticle = (APP_DEBUG ? null : S('index_indexArticle'));

		if (!$indexArticle) {
			foreach ($indexArticleType as $k => $v) {
				$indexArticle[$k] = M('Article')->where(array('type' => $v['name'], 'status' => 1, 'index' => 1))->order('id desc')->limit(6)->select();
			}

			S('index_indexArticle', $indexArticle);
		}

		$this->assign('indexArticle', $indexArticle);
		$indexLink = (APP_DEBUG ? null : S('index_indexLink'));

		if (!$indexLink) {
			$indexLink = M('Link')->where(array('status' => 1))->order('sort asc ,id desc')->select();
			S('index_indexLink', $indexLink);
		}

		$this->assign('indexLink', $indexLink);
                
                //查看分红币种
                $fenhong = M('fenhong_log') ->field(array('coinname','coinjian')) ->where(array('userid'=>session('userId'))) ->find();
                
                //显示三处份额(目前仅读出制定的币种：btc)
                $user_coin = M('user_coin_sep') ->where(array(
                                'userid' => session('userId'),
                                'coinname' => $fenhong['coinname'],
                                'coinjian' => $fenhong['coinjian']
                    )) ->find();
                $trade = $user_coin['trade_mum'] ? $user_coin['trade_mum']  : '0.00';
                $this ->assign('trade',$trade);
                
                $market = $user_coin['maket_mum'] ? $user_coin['maket_mum']  : '0.00';
                $this ->assign('market',$market);
                
                $user_coin_found_total = M('user_coin_sep') ->where(array(
                                'coinname' => $fenhong['coinname'],
                                'coinjian' => $fenhong['coinjian']
                    )) ->sum('fund_mum');
                
                $user_coin_found_total =  $user_coin_found_total ? $user_coin_found_total : '0.00';
                $this -> assign('user_coin_found_total',$user_coin_found_total);
                
                
		if (C('index_html')) {
			$this->display('Index/' . C('index_html') . '/index');
		}
		else {
			$this->display();
		}
	}

	public function monesay($monesay = NULL)
	{
	}

	public function install()
	{
	}
}

?>
