<style type="text/css">
	.table-header{
		font-weight: bold;
		text-align: right;
		width: 20%;
	}
	th{
		width: 150px;
	}
	#form-end-fail{
		padding: 15px;
		width: 300px;
		margin: 15px auto;
		text-align: center;
		background-color: #D64C75;
		color: #fff;			
	}
	#form-end-ok{
		padding: 15px;
		width: 300px;
		margin: 15px auto;
		text-align: center;
		background-color: #4FB36B;
		color: #fff;	
	}
</style>			
</script>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel">[<?php echo $model->name;?>]資料補充申請</h4>
</div>
<div class="modal-body">
	<?php if($check){ ?>
		<div id="form-end-ok">
			<h1><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>申請完成</h1>
			<p>請提供以下連結給供應商 : <br><?php echo Yii::app()->params['baseUrl']; ?>/registerSupplier/?id=<?php echo $model->id;?>&k=<?php echo $model->public_time;?></p>	
		</div>
	<?php }else{?>
		<div id="form-end-fail">
			<h1><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>申請失敗</h1>
			<p id="fail-msg">建立連結失敗!</p>	
		</div>	
	<?php }?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
</div>
