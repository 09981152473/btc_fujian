<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="renderer" content="webkit">
	<meta name="format-detection" content="telephone=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo C('web_title');?></title>
	<meta name="Keywords" content="<?php echo C('web_keywords');?>">
	<meta name="Description" content="<?php echo C('web_description');?>">
	<meta name="author" content="qijianke.com">
	<meta name="coprright" content="qijianke.com">
	<link rel="shortcut icon" href="favicon.ico"/>
	<link rel="stylesheet" href="/Public/Home/css/movesay.css"/>
	<link rel="stylesheet" href="/Public/Home/css/style.css"/>
	<link rel="stylesheet" href="/Public/Home/css/font-awesome.min.css"/>
	<script type="text/javascript" src="/Public/Home/js/jquery.min.js"></script>
	<script type="text/javascript" src="/Public/Home/js/jquery.flot.js"></script>
	<script type="text/javascript" src="/Public/Home/js/jquery.cookies.2.2.0.js"></script>
	<script type="text/javascript" src="/Public/Home/js/translate.js"></script>
	<script type="text/javascript" src="/Public/layer/layer.js"></script>
</head>
<body>
<div class="header bg_w" id="trade_aa_header">
	<div class="hearder_top">
		<div class="autobox po_re zin100" id="header">
			<div class="welcome"><?php echo C('top_name');?></div>
			<div class="right orange" id="login">
				<?php if(($_SESSION['userId']) > "0"): ?><dl class="mywallet">
						<dt id="user-finance">
						<div class="mywallet_name clear">
							<a href="/finance/"><?php echo (session('userName')); ?></a><i></i>
						</div>
						<div class="mywallet_list" style="display: none;">
							<div class="clear">
								<ul class="balance_list">
									<h4><?php echo L('public.keyongyue');?></h4>
									<li>
										<a href="javascript:void(0)"><em style="margin-top: 5px;" class="deal_list_pic_cny"></em><strong><?php echo L('public.renminbi');?>：</strong><span><?php echo ($userCoin_top['cny']*1); ?></span></a>
									</li>
								</ul>
								<ul class="freeze_list">
									<h4><?php echo L('public.weituodongjie');?></h4>
									<li>
										<a href="javascript:void(0)"><em style="margin-top: 5px;" class="deal_list_pic_cny"></em><strong><?php echo L('public.renminbi');?>：</strong><span><?php echo ($userCoin_top['cnyd']*1); ?></span></a>
									</li>
								</ul>
							</div>
							<div class="mywallet_btn_box">
								<a href="/finance/mycz.html"><?php echo L('public.chongzhi');?></a><a href="/finance/mytx.html"><?php echo L('public.tixian');?></a><a href="/finance/myzr.html"><?php echo L('public.zhuanru');?></a><a href="/finance/myzc.html"><?php echo L('public.zhuanchu');?></a><a href="/finance/mywt.html"><?php echo L('public.weituoguanli');?></a><a href="/finance/mycj.html"><?php echo L('public.chengjiaochaxun');?></a>
							</div>
						</div>
						</dt>
						
						<dd>
							ID：<span><?php echo (session('userId')); ?></span>
						</dd>
						<dd>
							<a href="<?php echo U('Login/loginout');?>"><?php echo L('public.tuichu');?></a>
						</dd>
					</dl>
					<?php else: ?> <!-- 登陆前 -->
					<div class="orange">
						<span class="zhuce"><a class="orange" href="<?php echo U('Login/register');?>"><?php echo L('public.zhuce');?></a></span> |
						<a href="javascript:;" class="orange" onclick="loginpop();"><?php echo L('public.denglu');?></a>
					</div><?php endif; ?>
			</div>
			<div class="right">
				<select id="select_lang" style="background-color: #F6F6F6;">
					<option <?php if((LANG_SET) == "zh-cn"): ?>selected<?php endif; ?> value="zh-cn">中文</option>
					<option <?php if((LANG_SET) == "en-us"): ?>selected<?php endif; ?> value="en-us">English</option>
				</select>
			</div>
			<div class="nav  nav_po_1" id="menu_nav">
				<ul>
					<li style=" text-align: right; margin-right: 20px;">
						<a href="/" id="index_box"><?php echo L('public.shouye');?></a>
					</li>
					<li>
						<a id="trade_box" href="<?php echo U('Trade/index');?>"><span><?php echo L('public.jiaoyizhongxin');?></span>
							<img src="/Public/Home/images/down.png"></a>
						<div class="deal_list " style="display: none;    top: 36px;">
							<dl id="menu_list_json"></dl>
							<div class="sj"></div>
							<div class="nocontent"></div>
						</div>
					</li>

					<?php if(is_array($daohang)): $i = 0; $__LIST__ = $daohang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
							<a id="<?php echo ($vo['name']); ?>_box" href="/<?php echo ($vo['url']); ?>"><?php echo (get_lang_text($vo['title'],$vo['title_en'])); ?></a>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>

				</ul>
			</div>
		</div>
	</div>
	<div style="clear: both;"></div>
	<div class="autobox clear" id="trade_clear">
		<div class="logo">
			<a href="/"><img src="/Upload/public/<?php echo ($C['web_logo']); ?>" alt=""/></a>
		</div>

		<!-- 头部QQ客服
		<div class="phone right">
			<span class="iphone" style=""></span><a href="http://wpa.qq.com/msgrd?V=3&amp;uin=<?php echo C('contact_qq')[0];?>&amp;Site=QQ客服&amp;Menu=yes" target="_blank" class="qqkefu"></a>
		</div>
		-->

	</div>
</div>
<script>
	var LANG_SET = '<?php echo (LANG_SET); ?>';
	
	$.getJSON("/Ajax/getJsonMenu?t=" + Math.random(), function (data) {
		if (data) {
			var list = '';
			for (var i in data) {
				list += '<dd><a href="/Trade/index/market/' + data[i]['name'] + '"><img src="/Upload/coin/' + data[i]['img'] + '" style="width: 18px; margin-right: 5px;">' + data[i]['title'] + '</a></dd>';
			}
			$("#menu_list_json").html(list);
		}
	});
	$('#trade_box').hover(function () {
		$('.deal_list').show()
	}, function () {
		$('.deal_list').hide()
	});
	$('.deal_list').hover(function () {
		$('.deal_list').show()
	}, function () {
		$('.deal_list').hide()
	});
	$('#user-finance').hover(function () {
		$('.mywallet_list').show();
	}, function () {
		$('.mywallet_list').hide()
	});
	$('#select_lang').change(function(){
		var self = $(this);
		if(self.val() == 'zh-cn'){
			window.location = '?l=zh-cn';
		}else{
			window.location = '?l=en-us';
		}
	});
</script>
<!--头部结束-->                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
<div class="autobox">
	<div class="now">
		<a href="/" class="orange"><?php echo L('finance.shouye');?></a> &gt; <a href="/finance/" class="orange"><?php echo L('finance.caiwuzhongxin');?></a> &gt; <?php echo L('finance.wodetuijian');?>
	</div>
	<div class="assets_center clear po_re zin70">
		<!--左侧菜单-->
		<div class="coin_menu">
	<div class="coin_menu_box">
		<ul>
			<li id="finance_index"><i class="coin_menu_op_0_1"></i><a href="/finance/index.html"><?php echo L('finance.caiwuzhongxin');?></a></li>
		</ul>
	</div>
	<div class="coin_menu_box">
		<ul>
			<li id="finance_mycz"><i class="coin_menu_op_18_1"></i><a href="/finance/mycz.html"><?php echo L('finance.renminbichongzhi');?></a></li>
			<li id="finance_mytx"><i class="coin_menu_op_2_1"></i><a href="/finance/mytx.html"><?php echo L('finance.renminbitixian');?></a></li>
			
		</ul>
	</div>
	<div class="coin_menu_box">
		<ul>
			<li id="finance_myzr"><i class="coin_menu_op_4_1"></i><a href="/finance/myzr.html"><?php echo L('finance.zhuanruxunibi');?></a></li>
			<li id="finance_myzc"><i class="coin_menu_op_5_1"></i><a href="/finance/myzc.html"><?php echo L('finance.zhuanchuxunibi');?></a></li>
		</ul>
	</div>
	<div class="coin_menu_box">
		<ul>
			<li id="finance_mywt"><i class="coin_menu_op_6_1"></i><a href="/finance/mywt.html"><?php echo L('finance.weituoguanli');?></a></li>
			<li id="finance_mycj"><i class="coin_menu_op_7_1"></i><a href="/finance/mycj.html"><?php echo L('finance.chengjiaochaxun');?></a></li>
		</ul>
	</div>
	<div class="coin_menu_box">
		<ul>
            <li id="finance_mytj"><i class="coin_menu_op_13_1"></i><a href="/finance/mytj.html"><?php echo L('finance.tuijianyonghu');?></a></li>
			<li id="finance_mywd"><i class="coin_menu_op_16_1"></i><a href="/finance/mywd.html"><?php echo L('finance.wodetuijian');?></a></li>
			<li id="finance_myjp"><i class="coin_menu_op_19_1"></i><a href="/finance/myjp.html"><?php echo L('finance.wodejiangpin');?></a></li>
		</ul>
	</div>
</div>
<script>
	//顶部菜单高亮
	$('.coin_menu_box a').hover(function(){var str=str_1=$(this).parent().find('i').attr('class');if(str.length>15)str=str.substring(0,str.length-2);$(this).parent().find('i').attr('class',str)},function(){$(this).parent().find('i').attr('class',str_1)});
</script>
		<!--右侧内容-->
		<form id="form-cnyin" class="assets_content w900 right bg_w">
			         <div class="safe_center clear" style="padding-left: 0px; border-bottom: 2px solid #e55600;">
    <h1 style="margin-top: 0px; margin-bottom: 15px; line-height: 15px;"><?php echo L('finance.wodetuijian');?></h1>
   </div>
   <div class="sj" style="top: 40px; left: 60px;"></div>
             <?php if(!empty($prompt_text)): ?><div class="mytips">
                        <h6 style="color: #ff8000;"><?php echo L('finance.wenxintishi');?></h6>
                        <?php echo ($prompt_text); ?>
                    </div><?php endif; ?>
   <br>


      <div class="cnyin_record" style="width: 918px;">
   
   <div class="f_body">
     <div class="f_body_main" style="display: block;">
      <div class="f_tab_body">
       <div>
        <table class="f_table" id="investLog_content">
         <thead>
          <tr>
           <th id="">ID</th>
           <th id=""><?php echo L('finance.yonghuming');?></th>
           <th id=""><?php echo L('finance.zhuceshijian');?></th>
           <th id=""><?php echo L('finance.shifourenzheng');?></th>
           <th id=""><?php echo L('finance.suoshuquyu');?></th>
          </tr>
         </thead>
          
         <tbody>
            <tr>
                <td><?php echo ($user["id"]); ?></td>
                <td><?php echo ($user["username"]); ?></td>
                <td><?php echo (addtime($user["addtime"])); ?></td>
                <td><?php if(empty($user["moble"])): ?><font class="buy"><?php echo L('finance.weirenzheng');?> </font> <?php else: ?> <font class="sell"><?php echo L('finance.yirenzheng');?></font><?php endif; ?></td>
                <td>
                    <?php if($user["area"] == 1): echo L('finance.suoshuquyuzhi1');?> 
                    <?php else: ?> 
                        <?php echo L('finance.suoshuquyuzhi0'); endif; ?>
                </td>  
           </tr>
         </tbody>
        </table>
           
                <input type="hidden" value="<?php echo ($data["id"]); ?>" id="myuid" />
           
           
           <div style="border:1px solid #ededed;">
               
               <form action="/Home/Finance/updateRelation" method="POST" enctype="multipart/form-data" >
              
                    <div style="margin-top:5px;width:98%;margin-left:2%;">
                        <h2>修改关系</h2>
                    </div>

                    <div id="area" style="margin-top:10px;width:98%;margin-left:2%;">
                        <label for="area">修改区域：</label> &nbsp;
                        <input type="radio" name="area" value="0" id="leftArea"  <?php if($user["area"] == 0): ?>checked = checked<?php endif; ?> />左区 &nbsp;&nbsp;
                        <input type="radio" name="area" value="1" id="rightArea" <?php if($user["area"] == 1): ?>checked = checked<?php endif; ?>  />右区
                    </div>

                    <div style="margin-top:15px;width:98%;margin-left:2%;">
                        <label for="invit_1">修改安置人：</label> &nbsp;
                        <select name="invit_1" style="width:90px;" id="invit_1">
                            <?php if(is_array($sameLayerUsers)): $i = 0; $__LIST__ = $sameLayerUsers;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["username"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <span id="invit_waring" style="display:none;font-size:12px;color:red;">(必须选择邀请人后才能修改)</span>
                        <!--
                        <span id="no_invit_waring" style="display:none;font-size:12px;color:red;">(没有合适的安置人，不可以发送邀请)</span>
                        -->
                    </div>
                    
                   <!--
                   <div style="margin-top:5px;margin-bottom:5px;width:98%;margin-left:2%;">
                       <span id="success" style="display:none;font-size:12px;color:green;">申请已经发送成功</span>
                       <span id="fail" style="display:none;font-size:12px;color:red;">申请发送失败,请重新申请</span>
                   </div>
                   -->
                       
                   
                   <div style="margin-top:5px;margin-bottom:5px;width:98%;margin-left:2%;">
                       <span id="notice_info" style="display:none;font-size:12px;color:green;"></span>
                   </div>
                   
                   
                   
                    
                    <div style="margin-top:15px;margin-bottom:10px;width:98%;margin-left:2%;padding-left:30px;"> 
                        <a href="#"  id="myupdate" style="width:80px;height:30px;background:#ededed;font-size:16px;letter-spacing:2px;padding:2px 10px;border:1px solid #333;" />申请修改</a>&nbsp;
                    </div>
                    
                    
               </form>
        
            </div>
               
           
       </div>
      </div>
     </div>
    </div>
   </div>
               
		</form>
              
	</div>
</div>

<br>
<br>

<script>

function Address(id){
	var img1="/Public/Home/images/jia.png";
	var img2="/Public/Home/images/jian.png";
	var img=$(".invit_img_"+id).attr("src");
	
	
	//alert(img)
	if(img==img1){
		$(".invit_img_"+id).attr("src",img2);
	
		$(".invit_"+id).show();
	}else{
		$(".invit_img_"+id).attr("src",img1);
		$(".invita_img_"+id).attr("src",img1);
		$(".invit_"+id).hide();
		$(".invita_"+id).hide();
	}
}

function Addressb(id){
	var img1="/Public/Home/images/jia.png";
	var img2="/Public/Home/images/jian.png";
	var img=$(".invit_img_"+id).attr("src");
	
	
	//alert(img)
	if(img==img1){
		$(".invit_img_"+id).attr("src",img2);
		
		$(".invit_"+id).show();
	}else{
		$(".invit_img_"+id).attr("src",img1);
		$(".invit_"+id).hide();
		$(".invita_"+id).hide();
	}
}


</script>
<script>
$("title").html("<?php echo C('web_title');?> - <?php echo L('finance.caiwuzhongxin');?> - <?php echo L('finance.renminbichongzhi');?>"); 
	//菜单高亮
    $('#finance_box').addClass('active');
    $('#finance_mywd').addClass('active');
    $('#finance_mywd i').attr('class','coin_menu_op_16');
    
    
    //设置左侧菜单高亮
    $("#finance_mywd").addClass('active');
    
</script>
<style>
	.footer{
		clear:both;
	}

	.footer .main{
		height:240px;
	}

	#footer a{
		color:#FFF;
		margin:0px 0px;
	}

	.footer .bottom{
		height:80px;
		background:#2C2C2C;
	}

	.footer .main .list{
		float:left;
		margin-top:40px;
		width: 185px;
		padding: 0px 5px;
	}

	.footer .main .list label{
		margin-top:10px;
		display:block;
		font-weight:bold;
		color:#FFF;
		font-size:16px;
		text-align: left;
		padding-left: 45px;
	}

	.footer .main .list ul{
		margin:10px 0px 0px;
		padding:0px;
	}

	.footer .main .list li{
		display:block;
		height:30px;
		line-height:30px;
		color:#CCC;
		text-align:center;
		list-style:none;
		text-align: left;
		padding-left: 45px;
	}

	.footer .main .list li a{
		display:block;
		width:100%;
		height:100%;
		color:#CCC;
		font-size:14px;
	}

	.footer .about_me{
		float:left;
		margin-top:40px;
		width:280px;
		height:150px;
		border-right:1px #606060 solid;
		padding-right:50px;
	}

	.footer .wx{
		margin-top:50px;
		height:55px;
	}

	.footer .wx a{
		position:relative;
		margin:0 14px;
		cursor:pointer;
	}

	.footer .wx a img{

		left:-69px;

		transition:300ms;
		-webkit-transition:300ms;
		-ms-transition:300ms;
		-o-transition:300ms;
		-moz-transition:300ms;
	}

	.footer .wx a:hover img{
		display:block;
		top:-180px;
	}

	.footer .footer_wx_icon{
		float:left;

		border-radius:55px;
		-webkit-border-radius:55px;
		-ms-border-radius:55px;
		-o-border-radius:55px;
		-moz-border-radius:55px;

		transition:300ms;
		-webkit-transition:300ms;
		-ms-transition:300ms;
		-o-transition:300ms;
		-moz-transition:300ms;
	}

	.footer .footer_wx_icon:hover{

	}

	.footer .footer_sn_icon{
		float:left;
		width:55px;
		height:55px;

		background-color:#34353A;

		border-radius:55px;
		-webkit-border-radius:55px;
		-ms-border-radius:55px;
		-o-border-radius:55px;
		-moz-border-radius:55px;

		transition:300ms;
		-webkit-transition:300ms;
		-ms-transition:300ms;
		-o-transition:300ms;
		-moz-transition:300ms;
	}

	.footer .footer_sn_icon:hover{

		background-color:#F00;
	}

	.footer .footer_qq_icon{
		float:left;
		width:55px;
		height:55px;

		background-color:#34353A;

		border-radius:55px;
		-webkit-border-radius:55px;
		-ms-border-radius:55px;
		-o-border-radius:55px;
		-moz-border-radius:55px;

		transition:300ms;
		-webkit-transition:300ms;
		-ms-transition:300ms;
		-o-transition:300ms;
		-moz-transition:300ms;
	}

	.footer .footer_qq_icon:hover{

		background-color:#F00;
	}

	.footer .about_me h4{
		margin:10px 0px 0px 44px;
		color:#FFF;
		font-size:14px;
		font-weight:normal;
	}

	.footer .about_me .about_me_text{
		margin-top:20px;
		margin-left:44px;
		font-size:14px;
		color:#CCC;
	}

	.footer .contact_us{
		float:left;
		margin-top:50px;
		padding-left:57px;
		border-left:1px #606060 solid;
		height:150px;
		color:#CCC;
		font-size:14px;
	}

	.footer .contact_us_text1{
		margin-top:6px;
		font-size:28px;
		color:#FFF;
	}

	.footer .contact_us_text2{
		margin-top:5px;
		font-size:12px;
	}

	.footer .contact_us_text3 span{
		float:left;
		line-height:31px;
	}

	.footer .contact_us_text3{
		margin-top:18px;
		display:block;
		color:#CCC;
	}

	.footer .contact_us_text3 i{
		display:block;
		float:left;
		margin-left:10px;
		width:32px;
		height:30px;
		cursor:pointer;
		border:1px #CCC solid;

		border-radius:16px;
		-webkit-border-radius:16px;
		-ms-border-radius:16px;
		-o-border-radius:16px;
		-moz-border-radius:16px;

		transition:300ms;
		-webkit-transition:300ms;
		-ms-transition:300ms;
		-o-transition:300ms;
		-moz-transition:300ms;

	}

	.footer .contact_us_text3 i:hover{
		border:1px #DB0015 solid;
		background-color:#DB0015;
	}

	.footer .bottom .text{
		float:left;
		margin-top:34px;
		color:#999;
		font-size:14px;
	}

	.footer .bottom .g{
		float:right;
		margin-right:10px;
	}

	.footer .bottom .g a{
		float:left;
		margin-left:20px;
		margin-top:24px;
		width:100px;
		height:36px;
	}
</style>
<footer id="footer" class="footer" style="padding: 0px 0px 20px 0px;">
	<section class="main">
		<div class="about_me">
			<div class="wx">
				<a href="javascript:" class="footer_wx_icon"><img src="/Upload/public/<?php echo ($C['footer_logo']); ?>"></a>
			</div>
		</div>
		<div class="layout_center">
			<?php if(is_array($footerArticleType)): $i = 0; $__LIST__ = $footerArticleType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="list"><label><?php echo (get_lang_text($vo['title'],$vo['title_en'])); ?></label>
					<ul>
						<?php if(is_array($footerArticle[$vo['name']])): $i = 0; $__LIST__ = $footerArticle[$vo['name']];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vvo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Article/index',array('id'=>$vvo['id']));?>" style="overflow: hidden;"><?php echo ($vvo['title']); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
						<li><a href="<?php echo U('Article/index',array('id'=>$vo['id']));?>" style="overflow: hidden;    text-align: left;"><?php echo L('public.gengduo');?></a></li>
					</ul>
				</div><?php endforeach; endif; else: echo "" ;endif; ?>





			<div class="contact_us">
				<div class="contact_us_text0" style="text-align: left;"><?php echo L('public.mianfeizixun');?> :</div>
				<div class="contact_us_text1" style="text-align: left;margin-top: 10px;margin-bottom: 12px;"><?php echo C('contact_moble');?></div>
				<div class="contact_us_text2" style="text-align: left;margin-bottom: 5px;"><?php echo L('public.gongzuoshijian');?></div>
				<?php $_result=C('contact_qqun');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="#" class="contact_us_text3"><span><?php echo L('public.huiyuanqunhao');?> :<?php echo ($i); echo L('public.qun');?>：<?php echo ($v); ?></span></a><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
		</div>
	</section>
</footer>
<div class="footer_bottom">
	<div class="autobox" style="height: 40px;margin-top: 5px;">
		<span style="display: inline-block;color:#A6A9AB;">CopyRight© 2013-2016 <?php echo ($C['web_name']); echo L('public.jiaoyipingtai');?> All Rights Reserved &nbsp;&nbsp;|&nbsp;&nbsp;<a href="http://www.miitbeian.gov.cn/publish/query/indexFirst.action" target="_blank"><?php echo ($C['web_icp']); ?></a><span style="display: inline-block; color:#A6A9AB"></span></span>
		<span style="float: right;">
			<a href="http://www.gov.cn/" target="_blank" class="margin10" style="margin-left:5px;"> <img src="/Upload/footer/footer_1.png">
			</a>
			<a href="http://www.szfw.org/" target="_blank" class="margin10" style="margin-left:5px;"> <img src="/Upload/footer/footer_2.png">
			</a>
			<a href="http://www.miibeian.gov.cn/" target="_blank" class="margin10" style="margin-left:5px;"><img src="/Upload/footer/footer_3.png">
			</a>
			<a href="http://www.cyberpolice.cn/" target="_blank" class="margin10" style="margin-left:5px;"><img src="/Upload/footer/footer_4.png">
			</a>
		</span>
	</div>
	<!-- 原安全验证位置 -->
</div>

<!--客服qq-->

<div id="all_mask" class="all_mask" style="height: 0px; display: none;"></div>
<div class="all_mask_loginbox" style="top: 313px; display: none;">
	<div class="login_title pl20"><?php echo L('public.denglu');?></div>
	<form id="form-login" class="mask_wrap login-fb">
		<div class="login_text zin90">
			<div class="mask_wrap_title"><?php echo L('public.zhanghao');?>：</div>
			<input id="login_username" name="username" type="text" placeholder="<?php echo L('public.shuruhuiyuanming');?>">
		</div>
		<div class="login_text zin80">
			<div class="mask_wrap_title"><?php echo L('public.mima');?>：</div>
			<input id="login_password" name="password" type="password" placeholder="<?php echo L('public.shurudenglumima');?>">
		</div>
		<?php if(($C['login_verify']) == "1"): ?><div class="login_text zin70" id="ga-box-i">
				<img id="codeImg reloadverifyindex" src="<?php echo U('Verify/code');?>" width="120" height="38" onclick="this.src=this.src+'?t='+Math.random()" style="margin-top: 1px; cursor: pointer;" title="<?php echo L('public.huanyizhang');?>">
				<input type="text" class="code" id="login_verify" name="code" placeholder="<?php echo L('public.shuruyanzhengma');?>" style="width: 106px; float: left;">
			</div><?php endif; ?>
		<div class="login_button">
			<input type="button" value="<?php echo L('public.denglu');?>" onclick="upLogin();">
		</div>
		<div class="login-footer wwxwwx" style="border-bottom-left-radius: 3px; border-bottom-right-radius: 3px;">
			<!--<a target="_blank" href="/"><img src="/Public/Home/images/qq2.png" style="vertical-align: text-bottom; padding-right: 5px;">zzQQ<?php echo L('public.denglu');?></a>-->

			<span style="color: #CCC; float: right; margin-right: 25px;">
			<a style="font-size: 12px;" href="<?php echo U('Login/register');?>"><?php echo L('public.mianfeizhuce');?></a>｜<a href="<?php echo U('Login/findpwd');?>" style="font-size: 12px;"><?php echo L('public.wangjimima');?></a></span>
		</div>
	</form>
	<div class="mask_wrap_close" onclick="wrapClose()"></div>
</div>
<script type="text/javascript" src="/Public/Home/js/jquery.cookies.2.2.0.js"></script>
<script>
	$('input').focus(function () {
		var t = $(this);
		if (t.attr('type') == 'text' || t.attr('type') == 'password')
			t.css({'box-shadow': '0px 0px 3px #1583fb', 'border': '1px solid #1583fb', 'color': '#333'});
		if (t.val() == t.attr('placeholder'))
			t.val('');
	});
	$('input').blur(function () {
		var t = $(this);
		if (t.attr('type') == 'text' || t.attr('type') == 'password')
			t.css({'box-shadow': 'none', 'border': '1px solid #e1e1e1', 'color': '#333'});
		if (t.attr('type') != 'password' && !t.val())
			t.val(t.attr('placeholder'));
	});


	function NumToStr(num) {
		if (!num) return num;
		num = Math.round(num * 100000000) / 100000000;
		num = num.toFixed(8);
		var min = 0.0001;
		var times = 0;
		var arr;
		if (num <= min) {
			times = 0;
			while (num <= min) {
				num *= 10;
				times++;
				if (times > 100) break;
			}
			num = num + '';
			arr = num.split(".");
			for (var i = 0; i < times; i++) {
				arr['1'] = '0' + arr['1'];
			}
			return arr[0] + '.' + arr['1'] + '';
		}
		return num.toFixed(8) + ' ';
	}


	function loginpop() {
		$('.all_mask').css({'height': $(document).height()});
		$('.all_mask').show();
		$('.all_mask_loginbox').show();
		$(".reloadverify").click();
	}

	var is_login = <?php echo (session('userId')); ?>
	;

	if (window.location.hash == '#login') {
		if (!is_login) {
			loginpop();
		}
	}

	if (is_login) {
		$.getJSON("/Ajax/allfinance?t=" + Math.random(), function (data) {

			$('#user_finance').html(data);
		});
	}


	function wrapClose() {
		$('.all_mask').hide();
		$('.all_mask_loginbox').hide();
	}

	var cookieValue = $.cookies.get('cookie_username');
	if (cookieValue != '' && cookieValue != null) {
		$("#login_username").val(cookieValue);
	}

	function upLogin() {
		var username = $("#login_username").val();
		var password = $("#login_password").val();
		var verify = $("#login_verify").val();
		if (username == "" || username == null) {
			layer.tips('<?php echo L('public.shuyonghuming');?>', '#login_username', {tips: 3});
			return false;
		}
		if (password == "" || password == null) {
			layer.tips('<?php echo L('public.shurudenglumima');?>', '#login_password', {tips: 3});
			return false;
		}

		$.post("<?php echo U('Login/submit');?>", {
			username: username,
			password: password,
			verify: verify,
		}, function (data) {
			if (data.status == 1) {
				$.cookies.set('cookie_username', username);
				layer.msg(data.info, {icon: 1});
				window.location = '/Finance';
			} else {
				//刷新验证码
				$(".reloadverifyindex").click();
				layer.msg(data.info, {icon: 2});
				if (data.url) {
					window.location = data.url;
				}
			}
		}, "json");
	}
</script></body></html>       

<script type="text/javascript">
    $(function(){
        
        var uid = <?php echo ($user["id"]); ?>;
        
        $("#leftArea,#rightArea").click(function(){
            $("#notice_info").text();
            $("#notice_info").hide();
            
            $("#invit_1").html('');
            var area = $(this).val();
            $.post('/Home/Finance/showUsers',{id:uid,area:area},function(data){
                
                if(data.status==0){
                    $("#notice_info").text(data.des);
                    $("#notice_info").show();
                    
                    $("#myupdate").hide();
                }
                
                if(data.status==1){
                    $("#notice_info").hide();
                    $("#myupdate").show();
                    
                    var html;
                    var info = data.info;
                    for(var i=0;i<info.length;i++){
                        html += '<option value="'+info[i].id+'">'+info[i].username+'</option>';
                    }
                
                    $("#invit_1").html(html);
                }

            })
        });
        
        
    })
</script>

<script type="text/javascript">
    $(function(){
        
        var cid = <?php echo ($loginUser); ?>;//登录用户
        var uid = <?php echo ($user["id"]); ?>; //谁被修改
        
        $("#myupdate").click(function(e){
            e.preventDefault();
    
            var area = $("#area input[name=area]:checked").val();
            
            var invit_1 = $("#invit_1").val();
            if(!invit_1){
                $("#invit_waring").show();
                return;
            }
            
            $.getJSON('/Home/Finance/updateRelation',{cid:cid,area:area,id:uid,invit_1:invit_1},function(data){
                if(data.status == 1 ){
                    $("#notice_info").text(data.des);
                    $("#notice_info").show();
                }else{
                    $("#notice_info").text(data.des);
                    $("#notice_info").show();
                }
            });
            
            
   
        });
        
    })
</script>



<style type="text/javascript">
    .diy{color:#0055FF}
</style>