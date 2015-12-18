<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/locales/bootstrap-datepicker.zh-TW.min.js" charset="UTF-8"></script>
<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/css/mediaReport.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/js/advertiserReport.js" charset="UTF-8"></script>
<div id="filter">
	<div id="filter-open-btn">
		<span class="glyphicon glyphicon-filter" aria-hidden="true"></span>
	</div>
	<div id="filter-list">
		<h2>篩選報表</h2>	
		<div class="panel panel-default">
			<div class="panel-heading">查詢條件</div>
			<div class="panel-body">	

				<div class="filter-box">
					<div class="form-group id-input-group">
						<div class="input-group">
							<div class="input-group-addon">訂</div>
							<input type="text" class="input-sm form-control id-input" id="Campaign_id" value="<?php echo $_GET['supplier_id'];?>">
						</div>
					</div>	
				</div>

			</div>		
		</div>
		<?php
			echo $this->renderPartial('_datepick');
		?>				
		<div class="filter-box">
			<button class="btn btn-default" id="run-day" type="submit">套用</button>
		</div>	
		<div class="filter-box">
			<a id="export" href="" class="btn btn-default"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span>下載報表</a>
		</div>	
		<div class="keys-alert"><small><span class="glyphicon glyphicon-tag" aria-hidden="true"></span>使用快捷鍵加速作業!!</small></div>
		<div class="keys-alert"><small>使用Shift + F 收合篩選</small></div>		
		<div class="keys-alert"><small>使用Shift + D 下載報表</small></div>
	</div>	
</div>
<div id="supplier-report">
	<div class="dropList-box">
	<?php
		echo $this->renderPartial('_reportDropList',array(
			'defReport'=>"加值功能報表",
		));
	?>
	</div>
	<div><h3>加值功能報表</h3></div>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active tabs-btn" id="def">
			<a href="#home" aria-controls="home" role="tab" data-toggle="tab">加值功能報表</a>
		</li>
		<li role="presentation" id="createNewTab" >
			<a href="#profile"  >
				<span class="glyphicon glyphicon-plus" aria-hidden="true">
			</a>
		</li>
	</ul>
	<div id="report-group">
		<div class="tab-body" id="def-body">
			<div class="display-supplier-report">請操作條件!</div>		
		</div>
	</div>
</div>
<script>
	var reportUrl = 'functionReport';
	<?php if((isset($_GET['Campaign_id']) && $_GET['Campaign_id'] > 0)){ ?>
		var type = "<?php echo $_GET['type']?>";
		$(function(){
			getReport();
		})		
	<?php }?>
</script>