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

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'yiiCGrid',
	'itemsCssClass' => 'table table-bordered',
	'dataProvider'=>$allData = $model->campaignList(),
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
			'name' => "campaign.id",
			'header' => "訂單編號",
			'value'=>'$data->campaign_id',
			'htmlOptions'=>array('width'=>'100','class'=>'day'),
			'filter'=>false,
		),		
		array(	
			'name' => "campaign.id",
			'header' => "訂單",
			'value'=>'$data->campaign->campaign_name',
			'htmlOptions'=>array('width'=>'100','class'=>'day'),
			'filter'=>false,
		),
		array(	
			'name' => "remaining_day",
			'header' => "剩餘走期",
			'value'=>'$data->remaining_day',
			'htmlOptions'=>array('width'=>'100','class'=>'day'),
			'filter'=>false,
		),
		array(	
			'name' => "booking_click",
			'header' => "總點擊",
			'value'=>'$data->booking_click',
			'htmlOptions'=>array('width'=>'100','class'=>'day'),
			'filter'=>false,
		),
		array(	
			'name' => "remaining_click",
			'header' => "剩餘點擊",
			'value'=>'$data->remaining_click',
			'htmlOptions'=>array('width'=>'100','class'=>'day'),
			'filter'=>false,
		),							
		array(	
			'name' => "day_click",
			'header' => "日點擊預估",
			'value'=>'$data->day_click',
			'htmlOptions'=>array('width'=>'100','class'=>'day'),
			'filter'=>false,
		),
		array(	
			'name' => "day_imp",
			'header' => "日曝光預估",
			'value'=>'$data->day_imp',
			'htmlOptions'=>array('width'=>'100','class'=>'day'),
			'filter'=>false,
		),
		array(	
			'name' => "day_budget",
			'header' => "日預算預估",
			'value'=>'$data->day_budget',
			'htmlOptions'=>array('width'=>'100','class'=>'day'),
			'filter'=>false,
		),		
	),
));

?>