<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie10 lt-ie9 lt-ie8 lt-ie7 ie6" lang="zh"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie10 lt-ie9 lt-ie8 ie7" lang="zh"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie10 lt-ie9 ie8" lang="zh"> <![endif]-->
<!--[if IE 9]> <html class="lt-ie10 ie9" lang="zh"> <![endif]-->
<!--[if gt IE 9]> <html class="gt-ie9" lang="zh"> <![endif]-->
<!--[if !IE]><!--> 
<html class="modern" lang="zh"> <!--<![endif]-->
	<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"> 
	<meta charset="utf-8">
	<title>CLICKFORCE EAS</title>
	<link rel="SHORTCUT ICON" href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/image/cfd.png">
	<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<script src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap/js/bootstrap.min.js"></script>
	<!-- <link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/css/main_list.css" rel="stylesheet"> -->
	<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
	<style type="text/css">
		#contents{
			width: 500px;
			margin-left: auto;
			margin-right: auto;
			/*margin-top: 100px;*/
			/*padding-top: 50px;*/
			background-color: #fff;
			border: 15px solid rgba(255, 255, 255, 0.2);
			-webkit-background-clip: padding-box; /* for Safari */
			background-clip: padding-box; /* for IE9+, Firefox 4+, Opera, Chrome */		
			opacity: 0.9;
			border-radius: 10px;		
		}
		html,body, #main{
			margin: 0px;
			padding: 0px;
			height: 100%;
			/*overflow: hidden;*/
		}	
		body{
			background-image: url("<?php echo Yii::app()->params['baseUrl']; ?>/assets/image/loginbg.jpg");
		    background-repeat: no-repeat;
		    background-position: 50% 50%;
		    background-size: auto;
		    background-attachment: fixed; 
		    /*margin-top: -20px;*/
		    padding-top: 100px;
		}
		#login-boxs{
			padding: 15px;
			width: 450px;
			margin-left: auto;
			margin-right: auto;		
		}
		.page-header{
			color: #9C9C9C;
			text-align: center;
		}
		#floor{
			width: 100%;
			position: absolute;
			bottom: 20px;
			margin-top: 10px;
			text-align: center;	
			color: #fff;			
		}
	</style>	
	<script type="text/javascript">
	$(function(){
		$(window).resize(function(){
			if($(window).width() >= $(window).height()){
				$("body").css("background-size","auto");
			}else{
				$("body").css("background-size","auto 100%");
			}
		});
	})
	</script>
	</head>
	<body>
		<div id="main">	
			<div id="contents">
				<div id="login-boxs">
					<?php echo $content; ?>
				</div>
			</div>
			<div id="floor">
				Copyright © 2015 Powered by ClickForce. All rights reserved
			</div>
		</div>
	</body>
</html>