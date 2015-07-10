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
	.topItem{
		border-bottom: solid 1px;
	}
/*	th{
		background-color: #DCDCDC;
	}*/
</style>
<h5>查詢時間 : <?php echo $day[0];?> ~ <?php echo $day[1];?></h5>
<h5>I/C : 曝光/點擊</h5>

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
	'dataProvider'=>$allData = $model->advertiserAccountsReport(),
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
			'name' => "campaign.advertiser.tos_id",
			'header' => "發票抬頭 .'<br'. 統一編號",
			'type' => "raw",
			'value'=>'"<div class=\'topItem\'>" .  "-" ."</div>" . $data->campaign->advertiser->short_name',
			//'htmlOptions'=>array('width'=>'100'),
			'filter'=>false,
		),					
		// array(
		// 	'name' => "campaign.advertiser.tos_id",
		// 	'header' => "統一編號",
		// 	'value'=>'',
		// 	'filter'=>false,
		// 	//'htmlOptions'=>array('width'=>'100'),
		// ),	
		// array(
		// 	'name' => "campaign_id",
		// 	'header' => "訂單編號",
		// 	'value'=>'$data->campaign_id',
		// 	'filter'=>false,
		// 	//'htmlOptions'=>array('width'=>'100'),
		// ),	
		array(
			'name' => "campaign_id",
			'header' => "訂單編號/名稱",
			'type' => "raw",
			'value'=>'"<div class=\'topItem\'>" . $data->campaign_id ."</div>" . $data->campaign->campaign_name',
			'filter'=>false,
			//'htmlOptions'=>array('width'=>'100'),
		),	
		array(
			'name' => "campaign.budget.total_budget",
			'header' => "訂單金額",
			'value'=>'"$".number_format(($data->budget->total_budget / 100), 0, "." ,",")',
			'filter'=>false,
			//'htmlOptions'=>array('width'=>'100'),
		),	
		array(
			'header' => "目標I/C",
			'value'=>'"<div class=\'topItem\'>" . (($data->budget->total_pv > 0) ? number_format($data->budget->total_pv, 0, "." ,",") : "-") . "</div>" . (($data->budget->total_click > 0) ? number_format($data->budget->total_click, 0, "." ,",") : "-")',
			'filter'=>false,
			'type' => "raw",
			//'htmlOptions'=>array('width'=>'100'),
		),		
		array(
			'header' => "CPM/CPC",
			'value'=>'"<div class=\'topItem\'>" . (($data->budget->total_pv > 0) ? "$".number_format(($data->budget->total_budget / 100) / $data->budget->total_pv, 2, "." ,",") : "-") . "</div>" . (($data->budget->total_click > 0) ? "$".number_format(($data->budget->total_budget / 100) / $data->budget->total_click, 2, "." ,",") : "-")',
			'filter'=>false,
			'type' => "raw",
			//'htmlOptions'=>array('width'=>'100'),
		),
		array(
			'name' => "campaign.start_time",
			'header' => "訂單走期",
			'type' => "raw",
			'value'=>'"<div class=\'topItem\'>" . date("Y-m-d", $data->campaign->start_time) ."</div>" . date("Y-m-d", $data->campaign->end_time)',
			'filter'=>false,
			'htmlOptions'=>array('width'=>'100'),
		),	
		array(
			'header' => "走期I/C",
			'value'=>'"<div class=\'topItem\'>" . (($data->impression_sum > 0) ? number_format($data->impression_sum, 0, "." ,",") : "-") . "</div>" . (($data->click_sum > 0) ? number_format($data->click_sum, 0, "." ,",") : "-")',
			'filter'=>false,
			'type' => "raw",
			//'htmlOptions'=>array('width'=>'100'),
		),	
		array(
			'header' => "查詢走期執行金額",
			'value'=>'"$".number_format($data->income_sum, 0, "." ,",")',
			'filter'=>false,
			//'htmlOptions'=>array('width'=>'100'),
		),	
		array(
			'header' => "已執行金額",
			'value'=>'"$".number_format($data->getCampaignAllIncome($data->campaign_id), 0, "." ,",")',
			'filter'=>false,
			//'htmlOptions'=>array('width'=>'100'),
		),		
		array(
			'header' => "尚未執行金額",
			'value'=>'"$".number_format(($data->budget->total_budget / 100) - $data->temp_income_sum, 0, "." ,",")',
			'filter'=>false,
			//'htmlOptions'=>array('width'=>'100'),
		),	
		array(
			'header' => "可請款金額",
			'value'=>'"$".number_format(($data->temp_income_sum > ($data->budget->total_budget / 100))? ($data->budget->total_budget / 100) : $data->temp_income_sum   , 0, "." ,",")',
			'filter'=>false,
			//'htmlOptions'=>array('width'=>'100'),
		),	

	),
));
?>