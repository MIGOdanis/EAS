<style type="text/css">
	#upm-list{
		display: none;
	}
</style>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php 
	$err = $form->errorSummary($model);
	if(!empty($err)):?>
	<div class="alert alert-danger" role="alert">
		<span class="sr-only">錯誤:</span>
		<?php echo $err; ?>
	</div>	
	<?php endif;?>


	<div class="form-group">
		<label><?php echo $form->labelEx($model,'name'); ?></label>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128 , "class"=>"form-control" , "placeholder"=>"暱稱")); ?>
		<p class="text-danger"><?php echo $form->error($model,'name'); ?></p>
	</div>	

	<?php if($model->isNewRecord):?>
		<div class="form-group">
			<label><?php echo $form->labelEx($model,'user'); ?></label>
			<?php echo $form->textField($model,'user',array('size'=>60,'maxlength'=>128 , "class"=>"form-control" , "placeholder"=>"帳號")); ?>
			<p class="text-danger"><?php echo $form->error($model,'user'); ?></p>
			<p class="help-block">帳號採e-mail格式</p>
		</div>

		<div class="form-group">
			<label><?php echo $form->labelEx($model,'password'); ?></label>
			<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>128 , "class"=>"form-control" , "placeholder"=>"密碼" )); ?>
			<p class="text-danger"><?php echo $form->error($model,'password'); ?></p>
		</div>
		
		<div class="form-group">
			<label><?php echo $form->labelEx($model,'repeat_password'); ?></label>
			<?php echo $form->passwordField($model,'repeat_password',array('size'=>60,'maxlength'=>128 , "class"=>"form-control" , "placeholder"=>"確認帳號")); ?>
			<p class="text-danger"><?php echo $form->error($model,'repeat_password'); ?></p>
		</div>

		<div class="form-group">
			<label><?php echo $form->labelEx($model,'group'); ?></label>
			<div><?php echo $form->dropDownList($model,'group',Yii::app()->params['userGroup'],array("class"=>"form-control")); ?></div>
			<p class="text-danger"><?php echo $form->error($model,'group'); ?></p>
		</div>

	<?php endif;?>

		<div class="form-group" id="upm-list">
			<label>選擇經銷商對應的TOS帳號(該經銷商將只能看到自己或下級的訂單)</label>
			<div id="upm-list-select">UPM清單載入中..</div>
		</div>

	<div class="form-group buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '儲存',array('class' => 'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript">
	var isNewRecord = '<?php echo ($model->isNewRecord)? "1" : "0"; ?>';
	var supplierId = '<?php echo $model->supplier_id?>'; 
	var group;

	$(function(){
		getUpmList();
		$("#User_group").change(function(){
			getUpmList();
		})
	})

	function getUpmList(){
		if(isNewRecord == 1){
			group = $("#User_group").val();
		}else{
			group = '<?php echo $model->group; ?>';	
		}

		console.log(group);
		
		if(group == 8){
			$.ajax({
					url: "getUpmList",
					success:function(html){
						$('#upm-list-select').html(html);
						$("#select-creater").val(supplierId);
						$('#upm-list').show();
					}
				})
			.fail(function(e) {
				if(e.status == 403){
					alert('您的權限不足 : 請取得[存取UPM帳號]');
				}
				if(e.status == 500){
					alert('現在無法取得UMP清單，請稍後在試');
				}            
			});			
		}else{
			$('#upm-list').hide();
		}
	}
</script>