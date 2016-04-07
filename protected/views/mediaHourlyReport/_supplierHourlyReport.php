<style type="text/css">
	#yiiCGrid{
		text-align: left;
	}
	table{
		border: solid 1px #ACACAC;
	}	
	.day{
		font-weight: bold;
	}
/*	th{
		background-color: #DCDCDC;
	}*/
</style>
<?php $day = (empty($_GET["day"])? date("Y-m-d") : $_GET["day"])?>
<h5>查詢日期 : <?php echo $day;?></h5>

<?php if( (isset($_GET['supplierId']) && $_GET['supplierId'] > 0) || (isset($_GET['siteId']) && $_GET['siteId'] > 0)  || (isset($_GET['adSpaceId']) && $_GET['adSpaceId'] > 0)){ ?>
	<h5>供應商 : <?php echo CHtml::link($supplier->name,array("mediaReport/supplierHourlyReport","supplier_id"=>$supplier->tos_id, "type" => $_GET["type"], "day" => $day)); ?></h5>
<?php }?>

<?php if( (isset($_GET['siteId']) && $_GET['siteId'] > 0)  || (isset($_GET['adSpaceId']) && $_GET['adSpaceId'] > 0)){ ?>
	<h5>網站 : <?php echo CHtml::link($supplier->site[0]->name,array("mediaReport/supplierHourlyReport","site_id"=>$supplier->site[0]->tos_id, "type" => $_GET["type"], "day" => $day)); ?></h5>
<?php }?>

<?php if(isset($_GET['adSpaceId']) && $_GET['adSpaceId'] > 0){ ?>
	<h5>版位 : <?php echo $supplier->site[0]->adSpace[0]->name; ?></h5>
<?php }?>

<?php
if(isset($_GET['showNoPay']) && !empty($_GET['showNoPay'])){
	$NoPay = array(
		"all" => "包含墊檔",
		"hide" => "不含墊檔",
		"only" => "只有墊檔",
	)
?>
	<h5>墊檔 : <?php echo $NoPay[$_GET['showNoPay']]; ?></h5>
<?php }?>

<?php
Yii::app()->clientScript->registerScript('search', "
	$('.search-button, .sort-link').click(function(){
		$('.search-form').toggle();
		return false;
	});
	$('.search-form form').submit(function(){
		$('#yiiCGrid').yiiGridView('update', {
			data: $(this).serialize()
		});
		return false;
	});
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'yiiCGrid',
	'itemsCssClass' => 'table table-bordered',
	'dataProvider'=> $dataProvider,
	// 'filter'=>$model,
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
	'columns' => array(
		array(
			'name' => '時段',
			'type' => 'raw',
			'value' => '$data["settled_time"]',
			'footer'=>"總計",
		),
		array(
			'name' => '曝光',
			'type' => 'raw',
			'value' => 'number_format($data["impression"], 0, "." ,",")',
			'footer'=> number_format(sumColumn($dataProvider,"impression"), 0, "." ,","),
		),
		array(
			'name' => '點擊',
			'type' => 'raw',
			'value' => 'number_format($data["click"], 0, "." ,",")',
			'footer'=> number_format(sumColumn($dataProvider,"click"), 0, "." ,","),
		),
		array(
			'name' => '點擊率',
			'type' => 'raw',
			'value' => '(($data["impression"] > 0) ? round(($data["click"] / $data["impression"]) * 100, 2) : 0) . "%"'
		),				
		array(
			'name' => '媒體成本',
			'type' => 'raw',
			'value' => '"$" . number_format($data["media_cost"], 2, "." ,",")',
			'footer'=> number_format(sumColumn($dataProvider,"media_cost"), 0, "." ,","),
		),	
		array(
			'name' => 'eCPC',
			'type' => 'raw',
			'value' => '"$" . (($data["impression"] > 0) ? number_format(($data["media_cost"] / $data["impression"]) * 1000, 2, "." ,",") : 0)'
		),	
		array(
			'name' => 'eCPM',
			'type' => 'raw',
			'value' => '"$" . (($data["click"] > 0) ? number_format(($data["media_cost"] / $data["click"]), 2, "." ,",") : 0)'
		),																
	),
));

function sumColumn($model,$key){
	// print_r($model->rawData); exit;
	$keySum = 0;
	foreach ($model->rawData as $value) {
		$keySum += $value[$key];
	}

	return $keySum;
}
?>