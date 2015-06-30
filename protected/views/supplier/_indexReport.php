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
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'yiiCGrid',
	'itemsCssClass' => 'table table-bordered',
	'dataProvider'=>$model->supplierDailyReport($this->supplier->tos_id),
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
			'value'=>'round(($data->click / $data->pv) * 100, 2) . "%"',
			'filter'=>false,
		),		
		array(
			'header' => "預估收益",
			'name' => "media_cost",
			'value'=>'number_format($data->media_cost, 2, "." ,",")',
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