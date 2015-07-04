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
	'dataProvider'=>$allData = $model->supplierDailyReport($this->supplier->tos_id,"index"),
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
			'footer'=>"總計",
		),
		array(
			'header' => "曝光",
			'name' => "impression",
			'value'=>'number_format($data->impression, 0, "." ,",")',
			// 'htmlOptions'=>array('width'=>'80'),
			'filter'=>false,
			'footer'=>number_format($model->sumColumn($allData,"impression"), 0, "." ,","),			
		),			
		array(
			'name' => "click",
			'header' => "點擊",
			'value'=>'number_format($data->click, 0, "." ,",")',
			// 'htmlOptions'=>array('width'=>'80'),
			'filter'=>false,
			'footer'=>number_format($model->sumColumn($allData,"click"), 0, "." ,","),			
		),	
		array(
			'header' => "點擊率",
			'value'=>'(($data->impression > 0) ? round(($data->click / $data->impression) * 100, 2) : 0) . "%"',
			'filter'=>false,
			'footer'=> (($model->sumColumn($allData,"impression") > 0) ? round(($model->sumColumn($allData,"click") / $model->sumColumn($allData,"impression")) * 100, 2) : 0) . "%",			
		),		
		array(
			'header' => "預估收益",
			'name' => "media_cost",
			'value'=>'number_format($data->media_cost, 2, "." ,",")',
			'htmlOptions'=>array('width'=>'80'),
			'filter'=>false,
			'footer'=>number_format($model->sumColumn($allData,"media_cost"), 2, "." ,","),			
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