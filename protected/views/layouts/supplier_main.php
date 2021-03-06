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
	<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
	<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<script src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap/js/bootstrap.min.js"></script>
	<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/css/main_supplier.css" rel="stylesheet">
	<script src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/js/jquery-ui/jquery-ui.min.js"></script>
	<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
	<script src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/js/supplierMain.js"></script>
	<!--[if IE]>
	<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/css/ie.css" rel="stylesheet">
	<![endif]-->	
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-65730031-1', 'auto');
		ga('send', 'pageview');
	</script>

	</head>
	<body>
		<?php
		if(Yii::app()->user->id > 0){
			
			$this->widget('SupplierNaviWidget', array('controller'=>$this->id,'supplier'=>$this->supplier, 'user'=>$this->user));
		}
		?>
		<div id="main">	

			<div id="contents">	
				<?php echo $content; ?>
			</div>
		</div>
		<div class="modal fade" id="modal">
			<div class="modal-dialog modal-lg">
				<div class="modal-content" id="modal-content">

				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<script type="text/javascript">
			
		</script>	
	</body>
</html>