<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/js/tinymce/tinymce_config.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/js/jquery-ui/ss-theam/jquery-ui.css">
<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/locales/bootstrap-datepicker.zh-TW.min.js" charset="UTF-8"></script>

<style type="text/css">
	.input-group.date{
		width: 350px;
	}
	.rank{
		width: 100px;
	}
	#group_select{
		width: 120px;
	}
	#loading-user-list{
		display: none;
	}
	.user_id_chk{
		float: left;
		border: solid 1px #ddd;
		padding: 5px;
		margin: 5px;
		border-radius: 5px;
	}
</style>
<script>
var tinymce_editor; 

$(function(){
	$('.input-group.date').datepicker({
	    format: "yyyy/mm/dd",
	    language: "zh-TW",
	    forceParse: false,
	    calendarWeeks: true,
	    autoclose: true
	});

	if ($("#noExpireTime").is(":checked")) {
        $("#expire_time").hide();
    } else {
        console.log("false");
        $("#expire_time").show();

    }

	$("#noExpireTime").click(function(){
		if ($(this).is(":checked")) {
            $("#expire_time").hide();
        } else {
            console.log("false");
            $("#expire_time").show();
            $("#Message_expire_time").val("");
        }
	});

	$("#Message_user_group").change(function(){
		getUser();
	});

	function getUser(){
		var id = $("#Message_user_group").val();
		$.ajax({
			url:"getGroupUser",
			data: { id : id },
			type : "post",
			success:function(html){
				$('#display-user-list').html(html);
				$('#loading-user-list').hide();
				$(".all-select-btn").click(function(){
					$(".user_id_chk input[type=checkbox]").each(function(){
						$(this).prop("checked","true");
					})
				})
			}
		})
		.fail(function(e) {
			if(e.status == 403){
				alert('您的權限不足');
				window.location.reload();
			}
			if(e.status == 500){
				alert('請稍後再試，或聯繫管理人員');
			}            
		});		
		console.log();
	}

	$(".all-select-btn").click(function(){
		$(".user_id_chk input[type=checkbox]").each(function(){
			$(this).prop("checked","true");
		})
	})
})


</script>

<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'site-setting-form',
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

	<div class="form-group rank">
		<label><?php echo $form->labelEx($model,'rank'); ?></label>
		<div><?php echo $form->dropDownList($model,'rank',Yii::app()->params['messageRank'],array("class"=>"form-control")); ?></div>
		<p class="text-danger"><?php echo $form->error($model,'rank'); ?></p>
	</div>

	<div class="alert alert-info" role="alert">
		訊息 : 一般訊息通知<br>
		緊急通知 : 會出現醒目的標示在供應商平台並且在訊息列置頂
	</div>

	<div class="form-group">
		<label><?php echo $form->labelEx($model,'title'); ?></label>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>70 , "class"=>"form-control" , "placeholder"=>"標題")); ?>
		<p class="text-danger"><?php echo $form->error($model,'title'); ?></p>
	</div>

	<div class="form-group">
		<label><?php echo $form->labelEx($model,'body'); ?></label>
		<textarea name="Message[body]" rows="20" id="Message_body">
			<?php echo (isset($_POST['body'])) ? $_POST['body'] : $model['body'];?>
		</textarea>
		<p class="text-danger"><?php echo $form->error($model,'body'); ?></p>
	</div>

	<?php
	if($model->publish_time > 0){
		$publish_time_hour = date("H", $model->publish_time);
		$model->publish_time = date("Y/m/d", $model->publish_time);
	}	

	if($model->expire_time > 0){
		$expire_time_hour = date("H", $model->expire_time);
		$model->expire_time = date("Y/m/d", $model->expire_time);
	}
	?>

	<?php if($model->isNewRecord):?>

	<div class="panel panel-default">
		<div class="panel-heading">訊息效期</div>
		<div class="panel-body">
			<div class="form-group">
				<label><?php echo $form->labelEx($model,'publish_time'); ?></label>
				<div class="input-group date">
					<?php echo $form->textField($model,'publish_time',array("class"=>"form-control datepicker-readonly" , "placeholder"=>"", "readonly"=>"readonly")); ?>
					<span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
				
					<div>
					<?php 
						echo '<select name="publish_time_hour" class="form-control">';
						for($r=1;$r <= 24; $r++){
							$selected = ($publish_time_hour == $r)? "selected" : "";
							 echo  '<option value="' . $r . ':00"' . $selected . '>' . $r . ' : 00</option>';
						}
						echo '</select>';
					?>					
					</div>

				</div>
				<p class="text-danger"><?php echo $form->error($model,'publish_time'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'expire_time'); ?></label>
				<div class="input-group date" id="expire_time">
					<?php echo $form->textField($model,'expire_time',array("class"=>"form-control datepicker-readonly" , "placeholder"=>"", "readonly"=>"readonly")); ?>
					<span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
					<div>
					<?php 

						echo '<select name="expire_time_hour" class="form-control" >';
						for($r=1;$r <= 24; $r++){
							$selected = ($expire_time_hour == $r)? "selected" : "";
							 echo  '<option value="' . $r . ':00"' . $selected . '>' . $r . ' : 00</option>';
						}
						echo '</select>';
					?>					
					</div>				

				</div>				
				<p class="text-danger"><?php echo $form->error($model,'expire_time'); ?></p>
				<input type="checkbox" id="noExpireTime" name="noExpireTime" value="1" 
					<?php if(($model->isNewRecord && $_POST['noExpireTime'] == 1) || (!$model->isNewRecord  && $model->expire_time === 0)){ ?> 
						checked="true" 
					<?php }?>
				>無期限
			</div>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">發送目標</div>
		<div class="panel-body">
			<div id="group_select">
				<div class="form-group">
					<label>
						<?php echo $form->labelEx($model,'user_group'); ?>
						<button type="button" class="btn btn-primary btn-xs all-select-btn">全選</button> </label>
					<div><?php echo $form->dropDownList($model,'user_group',Yii::app()->params['userGroup'],array("class"=>"form-control")); ?></div>
					<p class="text-danger"><?php echo $form->error($model,'user_group'); ?></p>
				</div>			
			</div>
			<p class="text-danger"><?php echo $form->error($model,'user_id'); ?></p>
			<div id="loading-user-list">載入中</div>
			<div id="display-user-list">
			<?php
			if($user !== null){
				$user_id = explode(":", $model->user_id);

				$this->renderPartial('_getGroupUser',array(
					"user_id" => $user_id,
					"user" => $user
				));
			}
			?>				
			</div>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">郵件通知</div>
		<div class="panel-body">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			郵件會於您預約的推播時間生效時發送給收件人
			<div>
				<input type="checkbox" name="Message[cron_mail]" value="1" <?php if($model->cron_mail == 1){ ?> checked="true" <?php }?>>開啟預約發送
			</div>
		</div>
	</div>

	<?php endif; ?>


	<div class="form-group buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '儲存',array('class' => 'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->