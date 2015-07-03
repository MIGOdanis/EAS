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

$supplierColumns = array(
	'name' => "adSpace.site.supplier.id",
	'header' => "供應商",
	'value'=>'$data->adSpace->site->supplier->name',
	'htmlOptions'=>array('width'=>'250','class'=>'day'),
	'filter'=>false,
);

if(isset($_GET['site']) && $_GET['site'] > 0){

	$supplierColumns = array(
		'name' => "adSpace.site.id",
		'header' => "網站",
		'value'=>'$data->adSpace->site->name',
		'htmlOptions'=>array('width'=>'250','class'=>'day'),
		'filter'=>false,
	);

}
if(isset($_GET['adSpace']) && $_GET['adSpace'] > 0){

	$supplierColumns = array(
		'name' => "adSpace.name",
		'header' => "版位",
		'value'=>'$data->adSpace->name',
		'htmlOptions'=>array('width'=>'250','class'=>'day'),
		'filter'=>false,
	);

}

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'yiiCGrid',
	'itemsCssClass' => 'table table-bordered',
	'dataProvider'=>$model->supplierDailyReport($this->supplier->tos_id,"supplier"),
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
			'name' => "settled_time",
			'header' => "日期",
			'value'=>'date("Y/m/d",$data->settled_time)',
			'htmlOptions'=>array('width'=>'40','class'=>'day'),
			'filter'=>false,
		),		
		$supplierColumns,		
		array(
			'header' => "請求",
			'name' => "impression",
			'value'=>'number_format($data->impression, 0, "." ,",")',
			// 'htmlOptions'=>array('width'=>'80'),
			'filter'=>false,
		),		
		array(
			'name' => "pv",
			'header' => "曝光",
			'value'=>'number_format($data->pv, 0, "." ,",")',
			
			// 'htmlOptions'=>array('width'=>'80'),
			'filter'=>false,
		),		
		array(
			'name' => "click",
			'header' => "點擊",
			'value'=>'number_format($data->click, 0, "." ,",")',
			// 'htmlOptions'=>array('width'=>'80'),
			'filter'=>false,
		),	
		array(
			'header' => "點擊率",
			'value'=>'(($data->pv > 0) ? round(($data->click / $data->pv) * 100, 2) : 0) . "%"',
			'filter'=>false,
		),		
		array(
			'header' => "預估收益",
			'name' => "media_cost",
			'value'=>'"$" . number_format($data->media_cost, 2, "." ,",")',
			'htmlOptions'=>array('width'=>'120'),
			'filter'=>false,
		),	
		array(
			'header' => "eCPM",
			'value'=>'"$" . (($data->pv > 0) ? number_format(($data->media_cost / $data->pv) * 1000, 2, "." ,",") : 0)',
			'htmlOptions'=>array('width'=>'80'),
			'filter'=>false,
		),	
		array(
			'header' => "eCPC",
			'value'=>'"$" . (($data->click > 0) ? number_format(($data->media_cost / $data->click), 2, "." ,",") : 0)',
			'htmlOptions'=>array('width'=>'80'),
			'filter'=>false,
		),	

		// array(
		// 	'name' => "pv",
		// 	'htmlOptions'=>array('width'=>'80'),
		// 	'filter'=>false,
		// ),										
	),
));
?>