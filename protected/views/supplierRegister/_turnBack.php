<style type="text/css">
	.table-header{
		font-weight: bold;
		text-align: right;
		width: 20%;
	}
	th{
		width: 150px;
	}
	#form-end-ok{
		padding: 15px;
		width: 500px;
		margin: 15px auto;
		text-align: center;
		background-color: #F2C910;
		color: #fff;
		overflow: hidden;
	}
</style>
<script type="text/javascript">
	$('#yiiCGrid').yiiGridView('update', {
		data: $('#yiiCGrid').serialize()
	});	
</script>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel"><?php echo $model->name;?></h4>
</div>
<div class="modal-body">
	<div id="form-end-ok">
		<h1><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>退回</h1>
		<p>請提供以下網址供供應商修改:</p>
		<p><?php echo Yii::app()->params['baseUrl']; ?>/registerSupplier/?id=<?php echo $model->id;?>&k=<?php echo $model->public_time;?></p>	
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
</div>
