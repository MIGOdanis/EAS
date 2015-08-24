<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie10 lt-ie9 lt-ie8 lt-ie7 ie6" lang="zh"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie10 lt-ie9 lt-ie8 ie7" lang="zh"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie10 lt-ie9 ie8" lang="zh"> <![endif]-->
<!--[if IE 9]> <html class="lt-ie10 ie9" lang="zh"> <![endif]-->
<!--[if gt IE 9]> <html class="gt-ie9" lang="zh"> <![endif]-->
<!--[if !IE]><!-->
<html class="modern" lang="zh">
<!--<![endif]-->
	<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
	<meta name="viewport" content="width=720">
	<meta charset="utf-8">
	<title>CLICKFORCE EAS</title>
	<link rel="SHORTCUT ICON" href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/image/cfd.png">
	<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- <link href="<?php //echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet"> -->
	<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/css/main.css" rel="stylesheet">
	<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
	<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div>
			<nav class="navbar navbar-default">
				<div class="container-fluid">
					<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
					<a href="#">
						<img height="50" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/image/cf.png">
					</a>
					</div>			
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
							<!-- <li class="active"><a href="#">Link</a></li> -->
							
							<?php 
							if(isset($this->user->auth->auth) && !empty($this->user->auth->auth)){
								$naviArray = array();
								$authFristList = array();
								$authDecode = json_decode($this->user->auth->auth,true);
								foreach ($authDecode as $key => $value) {
									$naviArray[] = $key;
									$fristKey = array_keys($value);
									//print_r($fristKey); exit;
									$authFristList[$key] = array_shift($fristKey);
								}

								foreach ($this->nav as $navIndex => $value) { 
									if(Yii::app()->user->id && in_array($navIndex, $naviArray)){
								?>
									<li <?php if(in_array($this->id, $value['controllers'])){?>  class="active" <?php } ?>>
									<a href="<?php echo Yii::app()->createUrl($value['list'][$authFristList[$navIndex]]['url']); ?>">
									<?php echo $value['title'];?>
									</a>
									</li>
								<?php 
									}
								}
							}?>
							</li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
									<span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo Yii::app()->user->name;?><span class="caret"></span>
								</a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="#">修改資料</a></li>
									<li><a href="<?php echo Yii::app()->createUrl("user/repassword"); ?>">修改密碼</a></li>
									<li class="divider"></li>
									<li><a href="<?php echo Yii::app()->createUrl("login/out"); ?>"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> 登出</a></li>
								</ul>
							</li>
						</ul>				
					</div>
				</div>	
			</nav>			
		</div>
		<div id="main">	
			<?php echo $content; ?>
			<div class="modal fade" id="modal">
				<div class="modal-dialog">
					<div class="modal-content" id="modal-content">

					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

			<div class="modal fade bs-example-modal-lg" id="modal-lg">
				<div class="modal-dialog modal-lg">
					<div class="modal-content" id="modal-content-lg">
					
					</div>
				</div>
			</div>
		</div>		
		
		<script type="text/javascript">
		keyEvent = false;
		$(function(){
			var leftNavStatus = true;
			$(document).keydown(function(e) {
				if(e.keyCode == 16)
					keyEvent = true;

				if(keyEvent && e.keyCode == 81){
					keyEvent = false;
					if(leftNavStatus){
						leftNavStatus = false;	
						$("#left-main").animate({marginLeft:"-200px"});
						$("#right-main").animate({marginLeft:"0px"});
											
					}else{
						leftNavStatus = true;
						$("#left-main").animate({marginLeft:"0px"});
						$("#right-main").animate({marginLeft:"200px"});
					}

				}
			});
			$(document).keyup(function(e) {
				keyEvent = false;
			});			
		})
		</script>
	</body>
</html>