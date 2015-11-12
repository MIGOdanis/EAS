<script type="text/javascript">
$(function(){
	$('.save-btn').click(function(){
		var check = 1;
		if($("#SiteApply_name").val().length == 0){
			var check = 0;
		}

		if(check){
			$(".wait-btn").show();
			$(".save-btn").hide();
			$.ajax({
				url:"applySite?id=<?php echo $this->supplier->tos_id;?>" ,
				type:"post",
				data:$("#form").serialize(),
				dataType:"json",
				success:function(data){
					if(data.code == 1){
						alert("申請完成，我們將請專員聯絡您");
					}else{
						alert("現在無法提供申請服務，請撥打本公司專線");
					}	
					$(".wait-btn").hide();
					$(".save-btn").show();					
					window.location.reload();				
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
		}else{
			alert("必填[名稱]")
		}
	});	
})

</script>
<style type="text/css">
	.wait-btn{
		display: none;
	}
</style>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel">申請新網站</h4>
	<small>填寫完整資訊有利於加速您的審核作業</small>
</div>
<div class="modal-body">	
	<h5>供應商 : (<?php echo $this->supplier->tos_id;?>)<?php echo $this->supplier->name;?></h5>
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'form',
		'enableAjaxValidation'=>true,
		'htmlOptions' => array('enctype' => 'multipart/form-data'),
	)); ?>	
	<div id="form">
		<div class="form-group">
			<label><?php echo $form->labelEx($model,'name'); ?></label>
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"")); ?>
			<p class="text-danger"><?php echo $form->error($model,'name'); ?></p>
		</div>

		<div class="form-group">
			<label><?php echo $form->labelEx($model,'url'); ?></label>
			<?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"")); ?>
			<p class="text-danger"><?php echo $form->error($model,'url'); ?></p>
		</div>

		<div class="form-group">
			<label><?php echo $form->labelEx($model,'type'); ?></label><br>
			<input type="radio" name="SiteApply[type]" value="1" checked> PC
			<input type="radio" name="SiteApply[type]" value="2"> Mobile Web
			<input type="radio" name="SiteApply[type]" value="3"> App
		</div>		

		<div class="form-group">
			<label><?php echo $form->labelEx($model,'summary'); ?></label><br>
			<textarea class="form-control" rows="3" name="SiteApply[summary]"></textarea>		
		</div>	

	</div>
</div>
<div class="modal-footer">
	<?php echo CHtml::submitButton($model->isNewRecord ? '申請' : '儲存',array('class' => 'btn btn-primary save-btn')); ?>
	<button type="button" class="btn btn-primary wait-btn">遞交申請中..</button>
	<button type="button" class="btn btn-default close-btn" data-dismiss="modal">關閉</button>
</div>
<?php $this->endWidget(); ?>