<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel">
		<?php
		if($model === null){
			echo "找不到這則訊息";
		}else{
			echo $model->title;
		}
		?>
	</h4>
</div>
<div class="modal-body">	
		<?php
		if($model === null){
			echo "這則訊息不存在或已經失效! 詳情可洽您的專員";
		}else{
			echo $model->body;
		}
		?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default close-btn" data-dismiss="modal">關閉</button>
</div>