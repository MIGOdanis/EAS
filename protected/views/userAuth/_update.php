<style type="text/css">
/*	#save{
		display: none;
	}*/
</style>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel">選擇權限組</h4>
</div>
<div class="modal-body">
	<div class="form-group">
		<label><?php echo $model->name;?> 現在權限組 : <?php echo (!empty($model->auth->name))? $model->auth->name : "未設置" ;?></label>
		<select class="form-control" id="auth_id">
		<?php foreach ($authGroup as $value) {?>
			<option value="<?php echo $value->id?>" <?php if($model->auth->id == $value->id){ ?>selected="selected"<?php }?>><?php echo $value->name?></option>
		<?php }?>
		</select>		
	</div>
</div>
<div class="modal-footer">
	<a class="btn btn-primary" id="save" type="submit" name="yt0" >儲存</a>
	<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
</div>
<script type="text/javascript">
	$(function(){

		$("#auth_id").change(function(){
			$("#save").show();
		});

		//save
		$("#save").click(function() {
			$.ajax({
				type: 'POST',
				url:"update?id=<?php echo $_GET['id']?>",
				data: { auth_id : $("#auth_id").val() },
				datatype: "json",
				success:function(data){
					console.log(data.code);
					if(data.code == 1){
						alert("權限組儲存完成");
						window.location = "admin";
					}else{
						alert("權限組儲存失敗，請洽技術人員");
					}
				}
			})
	        .fail(function(e) {
	            if(e.status == 403){
	                window.location.reload();
	            }
	        });
			return false;//阻止a标签
		});
	})
</script>