<script type="text/javascript">
	$(".save-btn").click(function(){
		var uid = $("#select-user").val();
		$.ajax({
			url: "selectBelong?id=<?php echo $_GET['id']; ?>" ,
			type:"post",
			data:{ uid : uid },
			dataType:"json",
			success:function(data){
				if(data.code == 1){
					getReport();
					alert("儲存完成");
					$('#modal').modal('hide');
				}else{
					alert("儲存失敗");
				}					
			}	
		})
        .fail(function(e) {
            if(e.status == 403){
            	alert("您的權限不足");
                window.location.reload();
            }
            if(e.status == 500){
            	alert("請稍後再試，或聯繫管理人員");
            }            
        });
	})
</script>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel">設置訂單業務</h4>
</div>
<div class="modal-body">	
	<select class="form-control" id="select-user">
		<?php foreach ($user as $value) {?>
			<option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
		<?php }?>
	</select>
</div>
<div class="modal-footer">
	<?php echo CHtml::submitButton('儲存',array('class' => 'btn btn-primary save-btn')); ?>
	<button type="button" class="btn btn-default close-btn" data-dismiss="modal">關閉</button>
</div>