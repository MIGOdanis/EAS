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
	'dataProvider'=>$allData = $model->ytbReport($_GET['CampaignId']),
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
			'header' => "日期",
			'value'=>'$data["date"]',
			'htmlOptions'=>array('width'=>'100','class'=>'day'),
			'filter'=>false,
			'footer'=>"總計",
		),	
		array(	
			'header' => "策略",
			'value'=>'"(" . $data["strategyId"] . ")" . $data["strategy"]',
			//'htmlOptions'=>array('width'=>'100'),
		),		
		array(	
			'header' => "素材",
			'value'=>'"(" . $data["creativeId"] . ")" . $data["creative"]',
			//'htmlOptions'=>array('width'=>'100'),
		),	
		array(	
			'header' => "版位",
			'value'=>'"(" . $data["adspaceId"] . ")" . $data["adspace"]',
			//'htmlOptions'=>array('width'=>'100'),
		),	
		array(	
			'header' => "類別",
			'value'=>'$data["siteCategory"]',
			//'htmlOptions'=>array('width'=>'100'),
		),			
		array(	
			'header' => "收視數",
			'value'=>'$data["totView"]',
			'footer'=>number_format(sumColumn($allData,"totView"), 0, "." ,","),
		),
		array(	
			'header' => "25%收視數",
			'value'=>'$data["25"]',
			'footer'=>number_format(sumColumn($allData,"25"), 0, "." ,","),
		),		
		array(	
			'header' => "50%收視數",
			'value'=>'$data["50"]',
			'footer'=>number_format(sumColumn($allData,"50"), 0, "." ,","),
		),	
		array(	
			'header' => "75%收視數",
			'value'=>'$data["75"]',
			'footer'=>number_format(sumColumn($allData,"75"), 0, "." ,","),
		),	
		array(	
			'header' => "100%收視數",
			'value'=>'$data["100"]',
			'footer'=>number_format(sumColumn($allData,"100"), 0, "." ,","),
		),					
	),
));
?>