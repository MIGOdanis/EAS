<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/locales/bootstrap-datepicker.zh-TW.min.js" charset="UTF-8"></script>
<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/css/mediaReport.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/js/mediaReport.js" charset="UTF-8"></script>

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
							<div class="input-group-addon">供</div>
							<input type="text" class="input-sm form-control id-input" id="supplier-id" value="<?php echo $_GET['supplier_id'];?>">
						</div>
					</div>	
				</div>


				<div class="filter-box">
					<div class="form-group id-input-group">
						<div class="input-group">
							<div class="input-group-addon">網</div>
							<input type="text" class="input-sm form-control id-input" id="site-id" value="<?php echo $_GET['site_id'];?>">
						</div>
					</div>		
				</div>

				<div class="filter-box">
					<div class="form-group id-input-group">
						<div class="input-group">
							<div class="input-group-addon">版</div>
							<input type="text" class="input-sm form-control id-input" id="adSpace-id" value="<?php echo $_GET['adSpace_id'];?>">
						</div>
					</div>		
				</div>


				<div class="filter-box">
					<div class="btn-group nopay-btn-group" data-toggle="buttons">
						<label class="btn btn-primary <?php if(isset($_GET['showNoPay']) && $_GET['showNoPay'] == "all"){ echo "active"; }?>" data-status="all">
							<input type="radio" name="options" id="option1" autocomplete="off" checked> 包含墊檔 
						</label>
						<label class="btn btn-primary <?php if(isset($_GET['showNoPay']) && $_GET['showNoPay'] == "hide"){ echo "active"; }?>" data-status="hide">
							<input type="radio" name="options" id="option2" autocomplete="off"> 不含墊檔 
						</label>
						<label class="btn btn-primary <?php if(isset($_GET['showNoPay']) && $_GET['showNoPay'] == "only"){ echo "active"; }?>" data-status="only">
							<input type="radio" name="options" id="option3" autocomplete="off"> 只看墊檔 
						</label>
					</div>
				</div>
			</div>		
		</div>
		<?php
			echo $this->renderPartial('_datepick');
		?>		
		<div class="panel panel-default">
			<div class="panel-heading">主要維度</div>
			<div class="panel-body">
				<div class="filter-box">
					<div class="btn-group index-btn-group" data-toggle="buttons">
						<label class="btn btn-primary active" data-status="supplier">
							<input type="radio" name="options" id="option2" autocomplete="off" checked> 版位 
						</label>					
						<label class="btn btn-primary" data-status="date">
							<input type="radio" name="options" id="option1" autocomplete="off"> 日期 
						</label>
						<label class="btn btn-primary" data-status="campaign">
							<input type="radio" name="options" id="option3" autocomplete="off"> 訂單 
						</label>
					</div>
				</div>
			</div>
		</div>			
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
			'defReport'=>"版位日報表",
		));
	?>
	</div>
	<div><h3>供應商網站版位日報表</h3></div>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active tabs-btn" id="def">
			<a href="#home" aria-controls="home" role="tab" data-toggle="tab">網站版位日報表</a>
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
	var reportUrl = 'adSpaceReport';
	<?php if( (isset($_GET['supplier_id']) && $_GET['supplier_id'] > 0) || (isset($_GET['site_id']) && $_GET['site_id'] > 0) || (isset($_GET['adSpace_id']) && $_GET['adSpace_id'] > 0)){ ?>
		var type = "<?php echo $_GET['type']?>";
		$(function(){
			getReport();
		})		
	<?php }?>
	<?php if(isset($_GET['showNoPay']) && !empty($_GET['showNoPay'])){?>
		showNoPay = "<?php echo $_GET['showNoPay']; ?>";
	<?php }?>	
</script>