<script type="text/javascript">
	$(".save-btn").click(function(){
		var closePrice = $("#close_price").val();
		$.ajax({
			url: "selectActive?id=<?php echo $_GET['id']; ?>" ,
			type:"post",
			data:{ closePrice : closePrice },
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
	$(".reset-btn").click(function(){
		$.ajax({
			url: "selectActive?id=<?php echo $_GET['id']; ?>" ,
			type:"post",
			data:{ reset : 1 },
			dataType:"json",
			success:function(data){
				if(data.code == 1){
					getReport();
					alert("重啟完成");
					$('#modal').modal('hide');
				}else{
					alert("重啟失敗");
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
	<h4 class="modal-title" id="myModalLabel">結案設置</h4>
</div>
<div class="modal-body">
	
	<?php if($model->active == 0){?>
		<label>結案金額</label>
		<?php echo "$".number_format($model->close_price, 0, "." ,","); ?>
	<?php }else{?>
		<label>請填入結案金額</label>
		<input size="60" maxlength="255" class="form-control" value="<?php echo round($model->budget->total_budget / 100);?>" id="close_price" type="text">
	<?php }?>	
</div>
<div class="modal-footer">
	<?php if($model->active == 0){?>
		<button type="button" class="btn btn-primary reset-btn" data-dismiss="modal">重啟</button>
	<?php }else{?>
		<button type="button" class="btn btn-primary save-btn" data-dismiss="modal">結案</button>
	<?php }?>
	
	<button type="button" class="btn btn-default close-btn" data-dismiss="modal">關閉</button>
</div>