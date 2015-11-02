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
<?php
function sumColumn($data,$key){
	$keySum = 0;
	foreach ($data->getData() as $value) {
		$keySum += $value[$key];
	}

	return $keySum;
}
?>
<?php if(isset($campaign) && $campaign !== null){ ?>
	<h5>訂單 : <?php echo $campaign->campaign_name; ?></h5>
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
	'dataProvider'=>$allData = $model->ytbReport($_GET['CampaignId'],$this->user),
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
			'header' => "時間",
			'value'=>'date("Y-m-d",$data["settled_time"])',
			// 'htmlOptions'=>array('width'=>'120','class'=>'day  sm-text'),
			'filter'=>false,
		),	
		array(	
			'name' => "campaign_id",
			'header' => "訂單名稱",
			'value'=>'$data["campaign"]->campaign_name',
			// 'htmlOptions'=>array('width'=>'170','class'=>'sm-text'),
			'filter'=>false,
		),	
		array(	
			'name' => "strategy_id",
			'header' => "策略名稱",
			'value'=>'$data["strategy"]->strategy_name',
			// 'htmlOptions'=>array('width'=>'170','class'=>'sm-text'),
			'filter'=>false,
		),	
		array(	
			'name' => "creative_id",
			'header' => "素材名稱",
			'value'=>'$data["creative"]->creativeGroup->name',
			// 'htmlOptions'=>array('width'=>'170','class'=>'sm-text'),
			'filter'=>false,
			// 'footer'=>"總計",
		),	
		array(	
			'name' => "width_height",
			'header' => "尺寸",
			'value'=>'$data["data"]->width_height',
			'htmlOptions'=>array('width'=>'50','class'=>'sm-text'),
			'filter'=>false,
		),
		array(	
			'name' => "adSpace.site.category.mediaCategory.id",
			'header' => "媒體分類",
			'value'=>'$data["mediaCategory"]->name',
			// 'htmlOptions'=>array('width'=>'100','class'=>'sm-text'),
			'filter'=>false,
		),					
		array(
			'name' => "impression",
			'header' => "曝光",
			'value'=>'number_format($data["data"]->impression, 0, "." ,",")',
			'filter'=>false,
		),		
		array(
			'name' => "click",
			'header' => "點擊",
			'value'=>'number_format($data["data"]->click, 0, "." ,",")',
			'filter'=>false,
		),	
		array(
			'header' => "點擊率",
			'value'=>'(($data["data"]->impression > 0) ? round(($data["data"]->click / $data["data"]->impression) * 100, 2) : 0) . "%"',
			'filter'=>false,
		),	
		array(
			'header' => "收視",
			'value'=>'(int)$data["temp_table"]["totView"]',
			'filter'=>false,
		),	
		array(
			'header' => "收視率",
			'value'=>'(($data["data"]->impression > 0) ? round(($data["temp_table"]["totView"] / $data["data"]->impression) * 100, 2) : 0) . "%"',
			'filter'=>false,
		),	
		array(
			'header' => "25%收視",
			'value'=>'(int)$data["temp_table"]["25"]',
			'filter'=>false,
		),
		array(
			'header' => "50%收視",
			'value'=>'(int)$data["temp_table"]["50"]',
			'filter'=>false,
		),
		array(
			'header' => "75%收視",
			'value'=>'(int)$data["temp_table"]["75"]',
			'filter'=>false,
		),
		array(
			'header' => "100%收視",
			'value'=>'(int)$data["temp_table"]["100"]',
			'filter'=>false,
		)		
	),
));

?>