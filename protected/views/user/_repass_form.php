<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'site-setting-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php 
	$err = $form->errorSummary($model);
	if(!empty($err) || $passCheck):?>
	<div class="alert alert-danger" role="alert">
		<span class="sr-only">錯誤:</span>
		<?php 
		echo $err;
		if($passCheck)
			echo "<ul><li>舊密碼錯誤</li></ul>";
		?>
	</div>	
	<?php endif;?>

	<div class="form-group">
		<label>舊密碼</label>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>128 , "class"=>"form-control" , "placeholder"=>"舊密碼")); ?>
		<p class="text-danger"><?php echo $form->error($model,'password'); ?></p>
		<p class="text-danger"><?php if($passCheck){echo "舊密碼錯誤";}; ?></p>
	</div>

	<div class="form-group">
		<label>新密碼</label>
		<?php echo $form->passwordField($model,'new_password',array('size'=>60,'maxlength'=>128 , "class"=>"form-control" , "placeholder"=>"新密碼" )); ?>
		<p class="text-danger"><?php echo $form->error($model,'new_password'); ?></p>
	</div>
	
	<div class="form-group">
		<label>確認新密碼</label>
		<?php echo $form->passwordField($model,'repeat_password',array('size'=>60,'maxlength'=>128 , "class"=>"form-control" , "placeholder"=>"確認新密碼")); ?>
		<p class="text-danger"><?php echo $form->error($model,'repeat_password'); ?></p>
	</div>


	<div class="form-group buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '儲存',array('class' => 'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->