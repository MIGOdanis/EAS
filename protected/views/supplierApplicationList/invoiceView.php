<style type="text/css">
	.table-header{
		font-weight: bold;
		text-align: right;
		width: 20%;
	}
</style>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel">請款憑證 : <?php echo $model->supplier->name;?></h4>
</div>
<div class="modal-body">
<?php 
function tax($model){
	$tax = Yii::app()->params['taxType'][$model->supplier->type];
	if($model->supplier->type == 1 && $model->monies < 20000)
		$tax = 1;

	return "$" . number_format($model->monies * $tax, $floor, "." ,",");
	
}
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay' => '未填寫',
	'htmlOptions' => array('class'=>'table table-bordered'),
	'attributes'=>array(
		array(
			'name' => '供應商',
			'value' => $model->supplier->name,
		),	
		array(
			'name' => '請款總額(含稅)',
			'value' => tax($model),
		),				
		'invoice',
		array(
			'name' => '發票填寫',
			'value' => $model->invoiceChecker->name,
		),			
		array(
			'name' => '資料建立時間',
			'value' => (empty($model->invoice_time)) ? "無資料" : date("Y-m-d H:i",$model->invoice_time),
		),					
	),
)); ?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
</div>