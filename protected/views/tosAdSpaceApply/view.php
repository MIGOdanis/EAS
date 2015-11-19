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
	<h4 class="modal-title" id="myModalLabel"><?php echo $model->site->name;?>版位申請</h4>
</div>
<div class="modal-body">
<p>申請資料</p>
<?php 
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay' => '未填寫',
	'htmlOptions' => array('class'=>'table table-bordered'),
	'attributes'=>array(
		'site_id',
		array(
			'name' => '網站',
			'type' => 'raw',
			'value' => CHtml::link($model->site->name,array("tosSite/admin","id"=>$model->site->tos_id),array("target"=>"_new")),
		),
		'url',
		'position',
		'imp',
		'ctr',
		array(
			'name' => '是否輪播',
			'value' => ($model->is_only == 1) ? "是" : "否",
		),	
		'other_network',
		'size',
		'remark',
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
	<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
</div>