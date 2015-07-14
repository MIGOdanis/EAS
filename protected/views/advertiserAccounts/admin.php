<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/locales/bootstrap-datepicker.zh-TW.min.js" charset="UTF-8"></script>
<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/css/mediaReport.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/js/advertiserAccounts.js" charset="UTF-8"></script>

<div id="report">
	<div id="filter">
		<div class="filter-box">
			<a id="export" href=""><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
		</div>	
		<div class="filter-box">
			<div class="dropdown">
			  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			    <span id="supplier-report-dropup-now">昨天</span>
			    <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
			  	<li><a href="#" data-type="yesterday" class="select-report">昨天</a></li>
			    <li><a href="#" data-type="7day" class="select-report">最近7天</a></li>
			    <li><a href="#" data-type="30day" class="select-report">最近30天</a></li>
			    <li><a href="#" data-type="pastMonth" class="select-report">上個月</a></li>
			    <li><a href="#" data-type="thisMonth" class="select-report">本月</a></li>
			    <li><a href="#" data-type="thisMonth" class="select-report">自訂</a></li>			   
			  </ul>
			</div>
		</div>

		<div class="filter-box">
			<button class="btn btn-default" id="run-day" type="submit">套用</button>
		</div>		

		<div class="filter-box filter-datepicker">
			<div class="span5 col-md-5" id="sandbox-container">
				<div class="input-daterange input-group" id="datepicker">
			    <input type="text" class="input-sm form-control" id="startDay">
			    <span class="input-group-addon">至</span>
			    <input type="text" class="input-sm form-control" id="endDay">
				</div>
			</div>
		</div>
	
		<div class="filter-box">
			<div class="form-group id-input-group">
				<div class="input-group">
					<div class="input-group-addon">訂</div>
					<input type="text" class="input-sm form-control id-input" id="Campaign_id" value="<?php echo $_GET['supplier_id'];?>">
				</div>
			</div>			
		</div>

		<div class="filter-box">
			<select class="form-control" id="select-creater">
				<option>全部</option>
				<?php foreach ($creater as $value) {?>
					<option value="<?php echo $value->id;?>"><?php echo $value->real_name;?></option>
				<?php }?>
			</select>			
		</div>

		<div class="filter-box">
			<select class="form-control" id="select-active">
				<option>全部</option>
				<option value="1">已結案</option>
				<option value="2">未結案</option>
			</select>			
		</div>


	</div>
</div>
<div id="supplier-report">
<div id="supplier-report">
	<h3>經銷對帳查詢</h3>
	<div id="loading-supplier-report">載入中..</div>
	<div id="display-supplier-report">請操作條件!</div>
</div>
</div>
<script>
	var reportUrl = 'admin';
</script>