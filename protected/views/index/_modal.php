<style type="text/css">
	.modal-body img{
		max-width: 550px;
	}
	.modal-body{
		overflow: auto;
	}
</style>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><?php echo $model->title;?></h4>
		</div>
		<div class="modal-body">
			<?php echo $model->content;?>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
		</div>
	</div>
</div>