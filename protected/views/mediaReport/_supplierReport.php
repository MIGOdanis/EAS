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

<?php if(isset($_GET['supplierId']) && $_GET['supplierId'] > 0){ ?>
	<h5>供應商 : <?php echo $supplier->name; ?></h5>
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
	'dataProvider'=>$allData = $model->adminSupplierDailyReport($adSpacArray),
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
	'columns'=>array(
		array(
			'name' => "supplier.id",
			'header' => "供應商編號",
			'value'=>'$data->adSpace->site->supplier->tos_id',
			'htmlOptions'=>array('width'=>'80','class'=>'day'),
			'filter'=>false,
			'footer'=>'總計'
		),
		array(
			'name' => "adSpace.site.supplier.id",
			'header' => "供應商",
			'type' => 'raw',
			'value'=>'CHtml::link($data->adSpace->site->supplier->name,array("mediaReport/siteReport","supplier_id"=>$data->adSpace->site->supplier->tos_id, "type" => $_GET["type"], "startDay" => $_GET["startDay"], "endDay" => $_GET["endDay"], "showNoPay" => $_GET["showNoPay"]))',
			'htmlOptions'=>array('width'=>'250','class'=>'day'),
			'filter'=>false,
		),							
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
	),
));
?>