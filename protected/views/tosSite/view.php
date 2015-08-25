<style type="text/css">
	.table-header{
		font-weight: bold;
		text-align: right;
		width: 20%;
	}
</style>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel"><?php echo $model->name;?></h4>
	<small>資料時間 <?php echo date("Y-m-d H:i", $model->sync_time); ?></small>
</div>
<div class="modal-body">
<?php 
function typeStr($type){
	$typeArray = array("類別不存在","PC","Mobile","OTT");
	return $typeArray[$type];
}
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay' => '未填寫',
	'htmlOptions' => array('class'=>'table table-bordered'),
	'attributes'=>array(
		'tos_id',
		array(
			'name' => '供應商',
			'value' => $model->supplier->name,
		),
		'name',
		array(
			'name' => '網站類型',
			'value' => typeStr($model->type),
		),
		array(
			'name' => '網站分類',
			'value' => $model->category->mediaCategory->name,
		),		
		'domain',
		array(
			'name' => '資料建立時間',
			'value' => (empty($model->create_time)) ? "未發文" : date("Y-m-d H:i",$model->create_time),
		),
		array(
			'name' => 'TOS狀態',
			'value' => (($model->status) == -1) ? "停用" : "啟用",
		),		
		'description',			
	),
)); ?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
</div>