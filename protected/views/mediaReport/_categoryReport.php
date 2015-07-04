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
	'dataProvider'=>$allData = $model->supplierCategoryReport(),
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
		// array(	
		// 	'name' => "adSpace.site.category.mediaCategory.id",
		// 	'header' => "媒體分類編號",
		// 	'value'=>'$data->adSpace->site->category->mediaCategory->id',
		// 	'htmlOptions'=>array('width'=>'100','class'=>'day'),
		// 	'filter'=>false,
		// ),			
		array(	
			'name' => "adSpace.site.category.mediaCategory.id",
			'header' => "媒體分類",
			'value'=>'$data->adSpace->site->category->mediaCategory->name',
			'htmlOptions'=>array('width'=>'100','class'=>'day'),
			'filter'=>false,
			'footer'=>"總計",
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
			'header' => "廣告主花費",
			'name' => "income",
			'value'=>'"$" . number_format($data->income, 2, "." ,",")',
			'htmlOptions'=>array('width'=>'120'),
			'filter'=>false,
			'footer'=>"$" . number_format($model->sumColumn($allData,"income"), 2, "." ,","),
		),	
		array(
			'header' => "eCPM",
			'value'=>'"$" . (($data->impression > 0) ? number_format(($data->income / $data->impression) * 1000, 2, "." ,",") : 0)',
			'htmlOptions'=>array('width'=>'120'),
			'filter'=>false,
			'footer'=>"$" . (($model->sumColumn($allData,"impression") > 0) ? number_format(($model->sumColumn($allData,"income") / $model->sumColumn($allData,"impression")) * 1000, 2, "." ,",") : 0),

		),	
		array(
			'header' => "eCPC",
			'value'=>'"$" . (($data->click > 0) ? number_format(($data->income / $data->click), 2, "." ,",") : 0)',
			'htmlOptions'=>array('width'=>'120'),
			'filter'=>false,
			'footer'=>"$" . (($model->sumColumn($allData,"click") > 0) ? number_format(($model->sumColumn($allData,"income") / $model->sumColumn($allData,"click")), 2, "." ,",") : 0),

		),										
	),
));
?>