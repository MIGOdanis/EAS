<style type="text/css">
	#hide-form{
		display: none;
		padding-right: 15px;
	}
	.set-btn{
		cursor: pointer;
	}
</style>
<script type="text/javascript">
	$(function(){
		$(".set-btn").click(function(){
			$("#hide-form").show();
		});

		<?php if((isset($_POST['Supplier']) || $_GET['setting'] == 1) && $model->saveChk !== true){?>
			$("#hide-form").show();
		<?php }?>
	})
</script>
<div class="page-header">
  <h1>匯款資訊</h1>
</div>
<div>
	<?php if((empty($this->supplier->account_number) || empty($this->supplier->account_name)  || empty($this->supplier->bank_sub_id)  || empty($this->supplier->bank_id)) && !(isset($_POST['Supplier']) || $_GET['setting'] == 1)){?>
		<div class="alert alert-danger" role="alert">您尚未填妥匯款資訊，請聯繫您的窗口! (電洽 <?php echo Yii::app()->params["cfTel"]?>)</div>
	<?php }?>

	<?php if($model->saveChk === true){?>
		<!-- <div class="alert alert-success" role="alert"> 帳戶設定完成! </div> -->
	<?php }?>
	<!-- <h5><div class="set-btn"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>編輯</div></h5> -->
	
	<div><?php echo $this->supplier->bank_name . " " . $this->supplier->bank_id; ?></div>
	<div><?php echo $this->supplier->bank_sub_name . " " . $this->supplier->bank_sub_id; ?></div>
	<div><?php echo $this->supplier->account_name; ?></div>
	<div><?php if(!empty($this->supplier->account_number)) { echo  $this->supplier->account_number ; } ?></div>

	<div id="hide-form">
		<?php 
			// $this->renderPartial('_paymentSettingForm',array(
			// 	'model'=>$model,
			// ));
		?>
	</div>
</div>