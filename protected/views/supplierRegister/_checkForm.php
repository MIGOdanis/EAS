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
		var tosId = $("#tos_id").val();
		var url = $(this).data("url");
		if(tosId.length > 0){
			if(confirm("請確認TOS-ID是否為" + tosId)){
				$.ajax({
					url:url,
					type:"post",
					data:{tosId : tosId},
					dataType:"json",
					success:function(data){
						if(data.code == 1){
							$("#form-box").hide();
							$("#form-box").html("");
							$("#form-end-ok").show();
							$(".btn-save").hide();
						}else if(data.code == 2){
							$("#form-box").hide();
							$("#form-box").html("");
							$("#form-end-fail").show();
							$("#fail-msg").html("審核完成! 但供應商帳號建立失敗，請手動方式建立！");
							$(".btn-save").hide();
						}else if(data.code == 3){
							$("#form-box").hide();
							$("#form-box").html("");
							$("#form-end-fail").show();
							$("#fail-msg").html("審核完成! 但供應商建立失敗，請聯繫管理人員！");
							$(".btn-save").hide();
						}else if(data.code == 6){
							$("#form-box").hide();
							$("#form-box").html("");
							$("#form-end-fail").show();
							$("#fail-msg").html("審核完成! 但使用者帳號重複，請手動方式建立！");
							$(".btn-save").hide();
						}else if(data.code == 4){
							$("#form-end-fail").show();
							$("#fail-msg").html("TOS-ID 已經存在，請確認後再次啟用！");
						}else{
							$("#form-box").hide();
							$("#form-box").html("");
							$("#form-end-fail").show();
							$("#fail-msg").html("審核失敗，請聯繫管理人員！Err : " + data.code);
							$(".btn-save").hide();
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
			alert("ID未填入")
		}
		return false;//阻止a标签		
	});				
</script>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel"><?php echo $model->name;?></h4>
</div>
<div class="modal-body">
	<div id="form-end-ok">
		<h1><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>審核完成</h1>
		<p>審核完成! 帳號已經發送至供應商信箱!</p>	
	</div>
	<div id="form-end-fail">
		<h1><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>審核失敗</h1>
		<p id="fail-msg">審核完成! 帳號已經發送至供應商信箱!</p>	
	</div>	
	<div class="form-group" id="form-box">
		<div><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>請到TOS系統中建立供應商取得ID後填寫</div>
		<input size="60" maxlength="255" class="form-control" placeholder="TOS-ID" id="tos_id" type="text">

		<p>基本資料</p>
		<?php 
		$types = array("無資料","台灣個人", "國外個人", "台灣公司", "國外公司");
		$bank = array("國外銀行","國內銀行");
		$this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'nullDisplay' => '未填寫',
			'htmlOptions' => array('class'=>'table table-bordered'),
			'attributes'=>array(
				'country_code',
				'name',
				'tel',
				'email',
				'company_name',
				'company_address',
				'mail_address',
				array(
					'name' => '供應商類型',
					'value' => $types[$model->type],
				),		
				array(
					'name' => '資料建立時間',
					'value' => (empty($model->create_time)) ? "未填寫" : date("Y-m-d H:i",$model->create_time),
				),				
			),
		)); ?>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary btn-save" data-url="check?id=<?php echo $model->id;?>&type=2" data-dismiss="modal">啟動</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
</div>
