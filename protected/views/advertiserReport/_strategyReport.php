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
<?php if(isset($strategy) && $strategy !== null){ ?>
	<h5>策略 : <?php echo $strategy->strategy_name; ?></h5>
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
	'dataProvider'=>$allData = $model->strategyReport($_GET['CampaignId'],$_GET['StrategyId']),
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
			'name' => "campaign_id",
			'header' => "訂單編號",
			'value'=>'$data->campaign_id',
			'htmlOptions'=>array('width'=>'170','class'=>'sm-text'),
			'filter'=>false,
		),		
		array(	
			'name' => "campaign_id",
			'header' => "訂單名稱",
			'value'=>'$data->campaign->campaign_name',
			'htmlOptions'=>array('width'=>'170','class'=>'sm-text'),
			'filter'=>false,
		),
		array(	
			'name' => "strategy_id",
			'header' => "策略編號",
			'value'=>'$data->strategy_id',
			'htmlOptions'=>array('width'=>'170','class'=>'sm-text'),
			'filter'=>false,
		),		
		array(	
			'name' => "strategy_id",
			'header' => "策略名稱",
			'value'=>'$data->strategy->strategy_name',
			'htmlOptions'=>array('width'=>'170','class'=>'sm-text'),
			'filter'=>false,
		),	
		array(
			'name' => "impression",
			'header' => "曝光",
			'value'=>'number_format($data->impression, 0, "." ,",")',
			'htmlOptions'=>array('width'=>'120'),
			'filter'=>false,
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