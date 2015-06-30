<script type="text/javascript">
	$(function(){
		$(".all-select-btn").click(function(){
			var selectTag = $(this).data("st");
			console.log(selectTag);
			$(selectTag).each(function(){
				$(this).prop("checked","true");
			})
		});
		$(".all-clear-btn").click(function(){
			var selectTag = $(this).data("st");
			console.log(selectTag);
			$(selectTag).each(function(){
				$(this).prop("checked","");
			})
		});
	})
</script>
<div class="form">
<?php 
if(!$model->isNewRecord){
	$auths = json_decode($model->auth,true);
}

require dirname(__FILE__).'/../layouts/_set_menu.php';
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'site-setting-form',
	'enableAjaxValidation'=>false,
)); 
	$err = $form->errorSummary($model);
	if(!empty($err)):?>
	<div class="alert alert-danger" role="alert">
		<span class="sr-only">錯誤:</span>
		<?php echo $err; ?>
	</div>	
	<?php endif;?>

	<div class="form-group">
		<label><?php echo $form->labelEx($model,'name'); ?></label>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128 , "class"=>"form-control" , "placeholder"=>"權限組名稱")); ?>
		<p class="text-danger"><?php echo $form->error($model,'name'); ?></p>
	</div>

	<div class="form-group">
		<label><?php echo $form->labelEx($model,'auth'); ?></label>
		<?php foreach ($this->nav as $navIndex => $value) {?>
			<div class="panel panel-default">
				<div class="panel-heading"><?php echo $value['title'];?></div>
					<ul class="list-group">
						<?php foreach ($value['list'] as $listIndex => $list) {?>
							<li class="list-group-item">
								<h4 class="list-group-item-heading"><?php echo $list['title'];?>
									<button type="button" class="btn btn-primary btn-xs all-select-btn" data-st=".<?php echo $navIndex;?>-<?php echo $listIndex;?>">全選</button>
									<button type="button" class="btn btn-primary btn-xs all-clear-btn" data-st=".<?php echo $navIndex;?>-<?php echo $listIndex;?>">全不選</button>
								</h4>
								<?php foreach ($list['action'] as $action => $actionName) {?>
									<label class="checkbox-inline">
										<input type="checkbox" <?php if(!$model->isNewRecord && (isset($auths[$navIndex][$listIndex]) && in_array($action, $auths[$navIndex][$listIndex]))){ ?> checked="true" <?php }?> class="<?php echo $navIndex;?>-<?php echo $listIndex;?>" name="auth[<?php echo $navIndex;?>][<?php echo $listIndex;?>][]" value="<?php echo $action;?>">
										<?php echo $actionName;?>
									</label>
								<?php }?>					
							</li>
						<?php }?>
					</ul>
				</div>
		<?php }?>
	</div>

	<div class="form-group">
		<label><?php echo $form->labelEx($model,'group_id'); ?></label>
		<div><?php echo $form->dropDownList($model,'group_id',Yii::app()->params['userGroup']); ?></div>
		<p class="text-danger"><?php echo $form->error($model,'group_id'); ?></p>
	</div>

	<div class="form-group buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '儲存',array('class' => 'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->