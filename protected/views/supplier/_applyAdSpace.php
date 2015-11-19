<script type="text/javascript">
$(function(){
	$('.save-btn').click(function(){
		var check = 1;
		if($(".size-check:checked").length == 0 || $("#AdSpaceApply_position").val().length == 0){
			var check = 0;
		}

		if(check){
			$(".wait-btn").show();
			$(".save-btn").hide();			
			$.ajax({
				url:"applyAdSpace?id=<?php echo $site->id;?>" ,
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
			alert("必填[位置]與[尺寸]")
		}
	});	
})

</script>
<style type="text/css">
	.wait-btn{
		display: none;
	}
	.form-group{
		margin-top: 25px;
	}
</style>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel">申請新版位</h4>
	<small>填寫完整資訊有利於加速您的審核作業</small>
</div>
<div class="modal-body">	
	<h5>供應商 : (<?php echo $this->supplier->tos_id;?>)<?php echo $this->supplier->name;?></h5>
	<h5>網　站 : (<?php echo $site->tos_id;?>)<?php echo $site->name;?></h5>
	<h5>系　統 : <?php echo ($site->type == 1)? "CF" : "MF" ?></h5>
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'form',
		'enableAjaxValidation'=>true,
		'htmlOptions' => array('enctype' => 'multipart/form-data'),
	)); ?>	
	<div id="form">
		<div class="form-group">
			<label>廣告擺放位置</label>
			<?php echo $form->textField($model,'position',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"範例：首頁右側 / 內文中間 , 內文下方")); ?>
			<p class="text-danger"><?php echo $form->error($model,'position'); ?></p>
		</div>

		<div class="form-group">
			<label><?php echo $form->labelEx($model,'url'); ?></label>
			<div><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>您欲申請的版位放置頁面(app免填)</div>	
			<?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"")); ?>
			<p class="text-danger"><?php echo $form->error($model,'url'); ?></p>
		</div>

		<div class="form-group">
			<label>網站單日PV數</label>
			<?php echo $form->textField($model,'imp',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"")); ?>
			<p class="text-danger"><?php echo $form->error($model,'imp'); ?></p>
		</div>

		<div class="form-group">
			<label><?php echo $form->labelEx($model,'is_only'); ?></label><br>
			<div><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>目前是否有與其他廣告聯播網輪播</div>	
			<input type="radio" name="AdSpaceApply[is_only]" value="1"> 是
			<input type="radio" name="AdSpaceApply[is_only]" value="0" checked> 否
		</div>		

		<div class="form-group">
			<div>
				<label><?php echo $form->labelEx($model,'other_network'); ?></label>
				<div><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>承上，請舉例說明(可複選)</div>
			</div>
			<?php foreach (Yii::app()->params['otherNetwork'] as $name) {?>
				<label class="checkbox-inline">
					<input type="checkbox" name="AdSpaceApply[other_network][]" value="<?php echo $name;?>" class="size-check">
					<?php echo $name;?>
				</label>
			<?php }?>			
			<p class="text-danger"><?php echo $form->error($model,'other_network'); ?></p>
		</div>


		<div><label><?php echo $form->labelEx($model,'ctr'); ?></label></div>
		<div><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>請列出網站目前所放置廣告之CTR(點擊率)</div>
		<div class="input-group">
			<?php echo $form->textField($model,'ctr',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"")); ?>
			<span class="input-group-addon">%</span>
		</div>
		<p class="text-danger"><?php echo $form->error($model,'ctr'); ?></p>

		<div><label><?php echo $form->labelEx($model,'size'); ?></label></div>
		<div><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>欲申請之廣告版位尺寸，可複選</div>
		
		<?php 
		if($site->type == 1){
			$size = Yii::app()->params['cfAdSpace'];
		}else{
			$size = Yii::app()->params['mfAdSpace'];
		}

		foreach ($size as $size => $sizeName) {?>
			<label class="checkbox-inline">
				<input type="checkbox" name="AdSpaceApply[size][]" value="<?php echo $size;?>" class="size-check">
				<?php echo $sizeName;?>
			</label>
		<?php }?>	

		<div class="form-group">
			<label><?php echo $form->labelEx($model,'remark'); ?></label><br>
			<textarea class="form-control" rows="3" name="AdSpaceApply[remark]"></textarea>		
		</div>	

	</div>
</div>
<div class="modal-footer">
	<?php echo CHtml::submitButton($model->isNewRecord ? '申請' : '儲存',array('class' => 'btn btn-primary save-btn')); ?>
	<button type="button" class="btn btn-primary wait-btn">遞交申請中..</button>
	<button type="button" class="btn btn-default close-btn" data-dismiss="modal">關閉</button>
</div>
<?php $this->endWidget(); ?>