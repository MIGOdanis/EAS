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
<h5>查詢時間 : <?php echo $day[0];?> ~ <?php echo $day[1];?></h5>

<?php if( (isset($_GET['supplierId']) && $_GET['supplierId'] > 0) || (isset($_GET['siteId']) && $_GET['siteId'] > 0)){ ?>
	<h5>供應商 : <?php echo CHtml::link($supplier->name,array("mediaReport/supplierReport","supplier_id"=>$supplier->tos_id, "type" => $_GET["type"], "startDay" => $_GET["startDay"], "endDay" => $_GET["endDay"])); ?></h5>
<?php }?>

<?php if(isset($_GET['siteId']) && $_GET['siteId'] > 0){ ?>
	<h5>網站 : <?php echo CHtml::link($supplier->site[0]->name,array("mediaReport/siteReport","site_id"=>$supplier->site[0]->tos_id, "type" => $_GET["type"], "startDay" => $_GET["startDay"], "endDay" => $_GET["endDay"])); ?></h5>
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
$allData = $model->adminSiteDailyReport($adSpacArray);

$baseColumns = array(
	array(
		'name' => "impression",
		'header' => "曝光",
		'value'=>'number_format($data->impression, 0, "." ,",")',
		'filter'=>false,
		'htmlOptions'=>array('width'=>'120'),
		'footer'=>number_format($model->sumColumn($allData,"impression"), 0, "." ,","),
	),		
	array(
		'name' => "click",
		'header' => "點擊",
		'value'=>'number_format($data->click, 0, "." ,",")',
		'htmlOptions'=>array('width'=>'120'),
		'filter'=>false,
		'footer'=>number_format($model->sumColumn($allData,"click"), 0, "." ,","),
	),	
	array(
		'header' => "點擊率",
		'value'=>'(($data->impression > 0) ? round(($data->click / $data->impression) * 100, 2) : 0) . "%"',
		'filter'=>false,
		'htmlOptions'=>array('width'=>'120'),
		'footer'=> (($model->sumColumn($allData,"impression") > 0) ? round(($model->sumColumn($allData,"click") / $model->sumColumn($allData,"impression")) * 100, 2) : 0) . "%",
	),		
	array(
		'header' => "媒體成本",
		'name' => "media_cost",
		'value'=>'"$" . number_format($data->media_cost, 2, "." ,",")',
		'htmlOptions'=>array('width'=>'120'),
		'filter'=>false,
		'footer'=>"$" . number_format($model->sumColumn($allData,"media_cost"), 2, "." ,","),
	),	
	array(
		'header' => "eCPM",
		'value'=>'"$" . (($data->impression > 0) ? number_format(($data->media_cost / $data->impression) * 1000, 2, "." ,",") : 0)',
		'htmlOptions'=>array('width'=>'120'),
		'filter'=>false,
		'footer'=>"$" . (($model->sumColumn($allData,"impression") > 0) ? number_format(($model->sumColumn($allData,"media_cost") / $model->sumColumn($allData,"impression")) * 1000, 2, "." ,",") : 0),

	),	
	array(
		'header' => "eCPC",
		'value'=>'"$" . (($data->click > 0) ? number_format(($data->media_cost / $data->click), 2, "." ,",") : 0)',
		'htmlOptions'=>array('width'=>'120'),
		'filter'=>false,
		'footer'=>"$" . (($model->sumColumn($allData,"click") > 0) ? number_format(($model->sumColumn($allData,"media_cost") / $model->sumColumn($allData,"click")), 2, "." ,",") : 0),

	),		
);

//主要維度
if(isset($_GET['indexType']) && $_GET['indexType'] == "supplier"){
	$index = array(
		array(
			'name' => "site.id",
			'header' => "網站編號",
			'value'=>'$data->adSpace->site->tos_id',
			'htmlOptions'=>array('width'=>'80','class'=>'day'),
			'filter'=>false,
			'footer'=>'總計'
		),
		array(
			'name' => "adSpace.site.id",
			'header' => "網站",
			'type' => 'raw',
			'value'=>'CHtml::link(((!empty($data->adSpace->site->name)) ? $data->adSpace->site->name : "其他"),array("mediaReport/adSpaceReport","site_id"=>$data->adSpace->site->tos_id, "type" => $_GET["type"], "startDay" => $_GET["startDay"], "endDay" => $_GET["endDay"], "showNoPay" => $_GET["showNoPay"]))',	
			'htmlOptions'=>array('width'=>'250','class'=>'name'),
			'filter'=>false,
		),	
		array(
			'header' => "網站分類",
			'type' => 'raw',
			'value'=>'$data->adSpace->site->category->mediaCategory->name',	
			'htmlOptions'=>array('width'=>'80','class'=>'name'),
			'filter'=>false,
		),
	);
}

if(isset($_GET['indexType']) && $_GET['indexType'] == "date"){
	$index = array(
		array(
			'name' => "settled_time",
			'header' => "日期",
			'value'=>'date("Y-m-d",$data->settled_time)',
			'htmlOptions'=>array('width'=>'80','class'=>'day'),
			'filter'=>false,
			'footer'=>'總計'
		),
	);
}

if(isset($_GET['indexType']) && $_GET['indexType'] == "campaign"){
	$index = array(
		array(
			'name' => "campaign_id",
			'header' => "訂單編號",
			'value'=>'$data->campaign->tos_id',
			'htmlOptions'=>array('width'=>'80','class'=>'day'),
			'filter'=>false,
			'footer'=>'總計'
		),
		array(
			'name' => "campaign_id",
			'header' => "訂單名稱",
			'value'=>'(!empty($data->campaign->campaign_name)) ? $data->campaign->campaign_name : "其他"',
			'htmlOptions'=>array('width'=>'250','class'=>'day'),
			'filter'=>false,
		),
	);
}

$index = array_merge($index, $baseColumns);


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
	'dataProvider'=>$allData,
	'filter'=>$model,
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
	'columns'=>$index,
));
?>