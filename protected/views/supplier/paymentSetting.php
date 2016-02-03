<style type="text/css">
	#hide-form{
		display: none;
		padding-right: 15px;
	}
	.set-btn{
		cursor: pointer;
	}
	#payinfor{
		margin-top: 10px;
	}
	.table-bordered th{
		width: 20%;
		min-width: 200px;
		text-align: right;
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
<div id="payments">
	<div class="page-header">
	  <h1>匯款資訊</h1>
	</div>
	<div id="payinfor">
		<?php if((empty($this->supplier->account_number) || empty($this->supplier->account_name)  || empty($this->supplier->bank_sub_id)  || empty($this->supplier->bank_id)) && !(isset($_POST['Supplier']) || $_GET['setting'] == 1)){?>
			<div class="alert alert-danger" role="alert">您尚未填妥匯款資訊，請聯繫您的窗口! (電洽 <?php echo Yii::app()->params["cfTel"]?>)</div>
		<?php }?>

		<?php if($model->saveChk === true){?>
			<!-- <div class="alert alert-success" role="alert"> 帳戶設定完成! </div> -->
		<?php }?>
		<!-- <h5><div class="set-btn"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>編輯</div></h5> -->
		
		<?php $this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'nullDisplay' => '未填寫',
			'htmlOptions' => array('class'=>'table table-bordered'),
			'attributes'=>array(
				'invoice_name',
				'account_name',
				'account_number',
				'bank_name',
				'bank_id',
				'bank_sub_name',
				'bank_sub_id',
				array(
					'name' => '銀行類型',
					'value' => Yii::app()->params['bankType'][$model->bank_type],
				),
				'bank_swift',
				'bank_swift2',
				array(
					'name' => '帳戶影本',
					'type' => 'raw',
					'value' => (empty($model->bank_book_img)) ? "無資料" : '<img src="' . Yii::app()->params['baseUrl'] . "/upload/registerSupplier/" . $model->bank_book_img . '" alt="certificate_image" class="img-thumbnail">',
				),			
			),
		)); ?>

		<div id="hide-form">
			<?php 
				// $this->renderPartial('_paymentSettingForm',array(
				// 	'model'=>$model,
				// ));
			?>
		</div>
	</div>
</div>