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
		<h1>請款紀錄</h1>
		<h5>匯款日期為申請月份隔月的25號</h5>
	</div>
	<div>
	<?php 
	function tax($data){
		$tax = Yii::app()->params['taxType'][$data->supplier->type];
		if($data->supplier->type == 1 && $data->monies < 20000)
			$tax = 1;

		return $data->monies * $tax;
		
	}

	function taxDeductTot($data){
		$tax = tax($data);
		$taxDeduct = Yii::app()->params['taxTypeDeduct'][$data->supplier->type];

		if($data->supplier->type == 1 && $data->monies >= 20000)
			$taxDeduct = 0.9;

		return $tax * $taxDeduct;
		
	}

	function taxDeduct($data){
		$tax = tax($data);
		$taxDeduct = taxDeductTot($data);

		return $tax - $taxDeduct;
	}	
	?>

	<div id="select-box">
		<form method="post">
			查詢年度(依請款期間查詢)
			<select name="year" class="select-type">
			<?php for($y=2015; $y <= date("Y"); $y++) {?>
				<option value="<?php echo $y?>" 
					<?php if((isset($_GET['year']) && $_GET['year'] == $y) || (!isset($_GET['year']) && date("Y") == $y)){ ?>
						selected="selected"<?php }?>>
					<?php echo $y?>
				</option>
			<?php }?>
			</select>	
			<button type="submit" class="btn btn-primary btn-sm">查詢</button>
		</form>	

	</div>

	<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'yiiCGrid',
		'itemsCssClass' => 'table table-bordered table-striped',
		'ajaxUpdate'=>true,
		'afterAjaxUpdate'=>'tableEvent',
		'dataProvider'=>$model->paymentsHistroy($this->supplier->tos_id),
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
				'header'=>'申請日期',
				'name'=>'supplier_id',
				'type'=>'raw',
				'value'=>'date("Y-m-d",$data->application_time)',
				'htmlOptions'=>array('width'=>'100'),
			),
			array(
				'header'=>'申請期間',
				'value'=>'date("Y-m",$data->start_time) . " - " . date("Y-m",$data->end_time)',
				'htmlOptions'=>array('width'=>'80'),
				'filter'=>false,
			),		
			array(
				'header'=>'收益(未稅)',
				'name' => "monies",
				'value'=>'"$" . number_format($data->monies, 2, "." ,",")',
				'htmlOptions'=>array('width'=>'100'),
				'filter'=>false,
			),
			array(
				'header'=>'收益(含稅)',
				'name' => "monies",
				'value'=>'"$" . number_format(tax($data), 0, "." ,",")',
				'htmlOptions'=>array('width'=>'100'),
				'filter'=>false,
			),
			array(
				'header'=>'代扣稅額',
				'name' => "monies",
				'value'=>'"$" . number_format(taxDeduct($data), 0, "." ,",")',
				'htmlOptions'=>array('width'=>'100'),
				'filter'=>false,
			),	
			array(
				'header'=>'收益總額',
				'name' => "monies",
				'value'=>'"$" . number_format(taxDeductTot($data), 0, "." ,",")',
				'htmlOptions'=>array('width'=>'100'),
				'filter'=>false,
			),	
			array(
				'header'=>'憑證',
				'name' => "invoice",
				'value'=>'$data->invoice',
				'htmlOptions'=>array('width'=>'100'),
				'filter'=>false,
			),
			// array(
			// 	'name' => "status",
			// 	'type'=>'raw',
			// 	'value'=>'status($data)',
			// 	'htmlOptions'=>array('width'=>'90'),
			// 	'filter'=>false,
			// ),		
			// array(
			// 	'type'=>'raw',
			// 	'name'=>'certificate_status',
			// 	'value'=>'certificate_status($data,' . $accountsStatus->value . ')',
			// 	'filter'=>false,
			// ),	
			// array(
			// 	'type'=>'raw',
			// 	'name'=>'invoice',
			// 	'value'=>'invoice($data,' . $accountsStatus->value . ')',
			// ),															
			// array(
			// 	'header'=>'退回',
			// 	'type'=>'raw',
			// 	'value'=> '(' . $accountsStatus->value . ' == 1) ? CHtml::link("退回",array(),array("class"=>"btn btn-default sendback-btn","data-id" => $data->id)) : "關帳中"',
			// 	'htmlOptions'=>array('width'=>'55'),
			// ),
		),
	));

	?>
	</div>
</div>