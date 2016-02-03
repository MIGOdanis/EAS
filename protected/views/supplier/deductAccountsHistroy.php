<style type="text/css">
	table, th{
		text-align: center;		
	}
	#select-box{
		margin-top: 10px;
		margin-bottom: 10px;
		line-height: 30px;
	}
	#select-box select{
		height: 30px;
	}
</style>
<div id="payments">
	<div class="page-header">
		<h1>違規(扣款)紀錄</h1>
		<h5>匯款日期為申請月份隔月的25號</h5>
	</div>
	<div>
<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'yiiCGrid-AI',
		'itemsCssClass' => 'table table-bordered',
		'dataProvider'=>$model->deductAccountsHistroy($this->supplier->tos_id),
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
				'header' => "違規說明",
				'type' => "raw",
				'value'=>'$data->reson',
				'filter'=>false,
			),
			array(	
				'header' => "違規金額",
				'type' => "raw",
				'value'=>'"$".number_format($data->deduct, 0, "." ,",")',
				'filter'=>false,
				'htmlOptions'=>array('width'=>'130'),
			),
			array(	
				'header' => "違規日期",
				'value'=>'date("Y-m-d",$data->date)',
				'filter'=>false,
				'htmlOptions'=>array('width'=>'100'),
			),			
			array(
				'header'=>'扣款月份',
				'type'=>'raw',
				'value'=> '($data->status == 0)? $data->application_year ."-" . $data->application_month  : "待扣款"',
				'htmlOptions'=>array('width'=>'100')
			)													
		),
	));
	?>
	</div>
</div>