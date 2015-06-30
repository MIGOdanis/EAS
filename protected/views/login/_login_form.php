<script>
$(function(){
	// 表單檢查
	$('form #login-member').click(function(e){
		var form = 'form[name="login-member"]';
		var emptyInput = [];
		var i = 0;
		$('.alertify-logs').empty();

        // 輸入框未填
        $(form +' input[required]').add(form +' textarea[required]').each(function(){
        	if ($(this).val() == '') {
        		emptyInput[i] = $(this).data('name');
        		i++;
        	}
        });

        // 表單動作
        if (emptyInput.length > 0) {
			emptyInfo = '「' + emptyInput.join() + '」欄位未填寫';
		    Alertify.log.error(emptyInfo, 5000);
    	} else {
    		$(form).submit();
    	}
    });

})
</script>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-member',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'name'=>'login-member',
		'class'=>'form-horizontal',
	),
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
		<label class="">帳號</label>
		<div class="">
			<?php echo $form->textField($model,'user', 
					array('class'=>'form-control input-sm', 'data-name'=>'帳號', 'required'=>'requried')); ?>
		</div>
	</div>

	<div class="form-group">
		<label class="">密碼</label>
		<div class="">
			<?php echo $form->passwordField($model,'password', 
					array('class'=>'form-control input-sm', 'data-name'=>'密碼', 'required'=>'requried')); ?>					
		</div>
	</div>

	<div class="form-group login-button-group">
		<div class="col-md-offset-4 col-md-8 col-sm-offset-4 col-sm-8 text-right">
			<label>
				<?php echo $form->checkBox($model,'rememberMe'); ?> <?php echo $form->label($model,'rememberMe'); ?>
			</label>
			<button id="login-member" type="submit" class="btn btn-primary">會員登入</button>
		</div>
	</div>

<?php $this->endWidget(); ?>