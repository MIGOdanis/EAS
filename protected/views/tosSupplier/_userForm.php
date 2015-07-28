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
		display: none;
		padding: 15px;
		width: 300px;
		margin: 15px auto;
		text-align: center;
		background-color: #D64C75;
		color: #fff;			
	}
	#form-end-ok{
		display: none;
		padding: 15px;
		width: 300px;
		margin: 15px auto;
		text-align: center;
		background-color: #4FB36B;
		color: #fff;	
	}
</style>
<script type="text/javascript">
	$(".btn-save").click(function() {
		var mail = $("#mail").val();
		var name = $("#name").val();
		var url = $(this).data("url");
		if(mail.length > 0 && name.length > 0){
			if(confirm("請確認帳號為" + mail)){
				$.ajax({
					url:url,
					type:"post",
					data:{mail : mail, name : name},
					dataType:"json",
					success:function(data){
						if(data.code == 1){
							$("#form-box").hide();
							$("#form-box").html("");
							$("#form-end-ok").show();
							$(".btn-save").hide();
						}else{
							// $("#form-box").hide();
							// $("#form-box").html("");
							$("#form-end-fail").show();
							$("#fail-msg").html("建立失敗！請檢查帳號是否重複 Err : " + data.code);
							// $(".btn-save").hide();
						}
						$('#yiiCGrid').yiiGridView('update', {
							data: $('#yiiCGrid').serialize()
						});						
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
			}			
		}else{
			alert("請確認欄位")
		}
		return false;//阻止a标签		
	});				
</script>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel">[<?php echo $model->name;?>]新建帳號</h4>
</div>
<div class="modal-body">
	<div id="form-end-ok">
		<h1><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>建立完成</h1>
		<p>建立完成! 帳號已經發送至供應商信箱!</p>	
	</div>
	<div id="form-end-fail">
		<h1><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>建立失敗</h1>
		<p id="fail-msg">建立完成! 帳號已經發送至供應商信箱!</p>	
	</div>	
	<div class="form-group" id="form-box">
		<label>名稱</label>
		<div><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>將依照此信箱發送密碼通知</div>
		<input size="60" maxlength="255" class="form-control" placeholder="帳號" id="mail" type="text">

		<label>帳號名稱</label>
		<input size="60" maxlength="255" class="form-control" placeholder="名稱" id="name" type="text">
	</div>	
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary btn-save" data-url="supplierUserCreate?id=<?php echo $model->id;?>&type=2" data-dismiss="modal">建立</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
</div>
