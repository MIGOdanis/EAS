<style type="text/css">
	.table-header{
		font-weight: bold;
		text-align: right;
		width: 20%;
	}
	th{
		width: 150px;
	}
</style>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel"><?php echo $model->supplier->name;?>版位申請</h4>
</div>
<div class="modal-body">
<p>申請資料</p>
<?php 

function getSiteType($type){
	return Yii::app()->params['siteType'][$type];
}

$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay' => '未填寫',
	'htmlOptions' => array('class'=>'table table-bordered'),
	'attributes'=>array(
		'supplier_id',
		array(
			'name' => '供應商',
			'type' => 'raw',
			'value' => CHtml::link($model->supplier->name,array("tosSupplier/admin","supplier_id"=>$model->supplier_id),array("target"=>"_new")),
		),
		'url',
		array(
			'name'=>'type',
			'value'=>getSiteType($model->type),
			'htmlOptions'=>array('width'=>'90'),
			'filter'=>false,
		),	
		'summary',								
		array(
			'name'=>'status',
			'value'=>($model->status == 1)? "申請中" : "已處理",
			'htmlOptions'=>array('width'=>'90'),
			'filter'=>false,
		),	
		array(
			'name' => '申請資料建立時間',
			'value' => (empty($model->create_time)) ? "未填寫" : date("Y-m-d H:i",$model->create_time),
		),				
	),
)); ?>	
</div>
<div class="modal-footer">
	<?php if($model->status != 2): ?>
		<a class="btn btn-success set-btn" href="status?id=<?php echo $model->id;?>&type=2">完成處理</a>
	<?php endif; ?>
	<button type="button" class="btn btn-default close-btn" data-dismiss="modal">關閉</button>
</div>