<div class="page-header">
  <h1>供應商年度款項凍結查詢</h1>
</div>
<form method="get">
<div class="panel panel-default">
	<div class="panel-heading">篩選</div>
	<div class="panel-body">
		<p>
		查詢年度
		<select name="year">
		<?php for($y=2015; $y <= date("Y"); $y++) {?>
			<option value="<?php echo $y?>" 
				<?php if((isset($_GET['year']) && $_GET['year'] == $y) || (!isset($_GET['year']) && date("Y") == $y)){ ?>
					selected="selected"<?php }?>>
				<?php echo $y?>
			</option>
		<?php }?>
		</select>

		申請狀態
		<select name="application_type">
			<option value="" <?php if(!isset($_GET['application_type']) || empty($_GET['application_type'])){ ?>selected="selected"<?php }?>>全部</option>
			<option value="1" <?php if($_GET['application_type'] == 1){ ?>selected="selected"<?php }?>>未請款</option>
			<option value="2" <?php if($_GET['application_type'] == 2){ ?>selected="selected"<?php }?>>請款中</option>
			<option value="3" <?php if($_GET['application_type'] == 3){ ?>selected="selected"<?php }?>>已請款</option>
		</select>

		</p>
		<p>
			<button type="submit" class="btn btn-primary btn-sm">篩選</button>
			<a href="admin" class="btn btn-warning btn-sm">清除條件</a>
		</p>
	</div>
</div>
</form>
<form method="get">
<p>
	下載凍結款項對帳表 (本期:<?php echo date("Y",strtotime("-1 year")); ?>)
	<select name="year" class="select-type">
	<?php for($y=2015; $y <= date("Y"); $y++) {?>
		<option value="<?php echo $y?>" 
			<?php if((isset($_GET['year']) && $_GET['year'] == $y) || (!isset($_GET['year']) && (date("Y") - 1) == $y)){ ?>
				selected="selected"<?php }?>>
			<?php echo $y?>
		</option>
	<?php }?>
	</select>
	<input type="hidden" name="export" value="1">
	<input type="hidden" name="application_type" value="<?php echo $_GET['application_type'];?>">
	<button type="submit" class="btn btn-primary btn-sm">下載</button>
</p>
</form>
<?php 
function application($data){
		$application_type = array("未請款","請款中","已請款");
		return $application_type[$data->application_type];
}

function tax($data,$floor){
	$tax = Yii::app()->params['taxType'][$data->site->supplier->type];
	return number_format($data->total_monies * $tax, $floor, "." ,",");	
}


function supplierType($data){
	$types = array("未填","國人", "外人", "國司", "外司");
	$class = array("danger","default", "primary", "success", "warning");

	return '<span class="label label-' . $class[$data->site->supplier->type] . '">' . $types[$data->site->supplier->type] . '</span>';
}
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'yiiCGrid',
	'itemsCssClass' => 'table table-bordered table-striped',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>true,
	'afterAjaxUpdate'=>'tableEvent',	
	'summaryText'=>'共 {count} 筆資料，目前顯示第 {start} 至 {end} 筆',
	'emptyText'=>'沒有資料',
	'pager' => array(
		'nextPageLabel' => '»',
		'prevPageLabel' => '«',
		'firstPageLabel' => ' ',
		'lastPageLabel'=> ' ',
		'header' => ' ',
		'htmlOptions' => array('class'=>'pagination'),
		'hiddenPageCssClass' => '',
		'selectedPageCssClass' => 'active',
		'previousPageCssClass' => '',
		'nextPageCssClass' => ''
	),
	'template'=>'{pager}{items}{pager}',
	'columns'=>array(
		array(
			'name'=>'site.supplier.tos_id',
			'value'=>'$data->site->supplier->tos_id',
			'htmlOptions'=>array('width'=>'90'),
		),	
		array(
			'name'=>'supplier_id',
			'type'=>'raw',
			'value'=>'supplierType($data) . $data->site->supplier->name',
			'htmlOptions'=>array('width'=>'240'),
		),	
		array(
			'header'=>'總額',
			'name'=>'total_monies',
			'htmlOptions'=>array('width'=>'130'),
			'value'=>'number_format($data->total_monies, 2, "." ,",")',
			'filter'=>false,
		),		
		// array(
		// 	'header'=>'稅後總額(原始)',
		// 	'htmlOptions'=>array('width'=>'130'),
		// 	'value'=>'tax($data,2)',
		// 	'filter'=>false,
		// ),		
		// array(
		// 	'header'=>'稅後總額(進位)',
		// 	'htmlOptions'=>array('width'=>'130'),
		// 	'value'=>'tax($data,0)',
		// 	'filter'=>false,
		// ),					
		array(
			'header'=>'款項月份(起)',
			'name'=>'last_application',
			'value'=>'($data->last_application > 0) ? date("Y-m",$data->last_application) : date("Y-m")',
			'htmlOptions'=>array('width'=>'130'),
			'filter'=>false,
		),	
		array(
			'header'=>'款項月份(迄)',
			'name' => "this_application",
			'value'=>'date("Y-m",$data->this_application)',
			'htmlOptions'=>array('width'=>'130'),
			'filter'=>false,
		),									
		array(
			'header'=>'請款',
			'type'=>'raw',
			'value'=> 'application($data)',
			'htmlOptions'=>array('width'=>'71'),
		),
	),
));

?>