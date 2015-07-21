<style type="text/css">
	.table-header{
		font-weight: bold;
		text-align: right;
		width: 20%;
	}
</style>
<script type="text/javascript">
$('#SupplierApplicationLog_invoice_time').datepicker({
	format: "yyyy/mm/dd",
    language: "zh-TW",
    todayHighlight: true,
     autoclose: true,
});

var id = "<?php echo $model->id;?>";

$(function(){
	$('.create-btn').click(function(e) {
		var invoiceNum = $("#SupplierApplicationLog_invoice").val();
		var invoiceTime = $("#SupplierApplicationLog_invoice_time").val();
		if(confirm('是否儲存憑證號碼:\n' + invoiceNum)){
			
			var data = { id: id, invoiceNum :invoiceNum, invoiceTime : invoiceTime };
			$.post('invoice' , data, function( data ) {
				if(data.code == 1){
					alert('儲存成功');
					$('#yiiCGrid').yiiGridView('update', {
						data: $('#yiiCGrid').serialize()
					});
					updateInvoiceView();
				}else{
					alert('儲存失敗，請聯繫管理人員 #' + data.code);
				}
			},'json')
			.fail(function(e) {
			    if(e.status == 403){
			        alert('權限不足');
			    }
			});
		}
		return false;
    });

    $('.reset-btn').click(function() {
		if(confirm('請確認是否重設此憑證')){
			var data = { id:id };
			$.post('invoiceReset' , data, function( data ) {
				if(data.code == 1){
					alert('重設成功');
					$('#yiiCGrid').yiiGridView('update', {
						data: $('#yiiCGrid').serialize()
					});
					updateInvoiceView();
				}else{
					alert('重設失敗，請聯繫管理人員 #' + data.code);
				}
			},'json')
			.fail(function(e) {
			    if(e.status == 403){
			        alert('權限不足');
			    }
			});
		}
		return false;
    });

	function updateInvoiceView(){
		$.ajax({
			type: 'POST',
			url:'invoiceView',
			data: {id:id},
			success:function(html){
				$('#modal-content').html(html);
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
	}

});
</script>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel">請款憑證 : <?php echo $model->supplier->name;?></h4>
</div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'site-setting-form',
	'enableAjaxValidation'=>true,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>
<div class="modal-body">
	<?php 
	if(!empty($model->invoice)){
		function tax($model){
			$tax = Yii::app()->params['taxType'][$model->supplier->type];
			if($model->supplier->type == 1 && $model->monies < 20000)
				$tax = 1;

			return "$" . number_format($model->monies * $tax, $floor, "." ,",");
			
		}
		$this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'nullDisplay' => '未填寫',
			'htmlOptions' => array('class'=>'table table-bordered'),
			'attributes'=>array(
				array(
					'name' => '供應商',
					'value' => $model->supplier->name,
				),	
				array(
					'name' => '請款總額(含稅)',
					'value' => tax($model),
				),
				array(
					'name' => '憑證填寫',
					'value' => $model->invoiceChecker->name,
				),									
				'invoice',
				array(
					'name' => '憑證類型',
					'value' => Yii::app()->params["invoiceType"][$model->certificate_status],
				),			
				array(
					'name' => '憑證時間',
					'value' => (empty($model->invoice_time)) ? "無資料" : date("Y-m-d",$model->invoice_time),
				),					
			),
		));
	}else{
		$model->invoice = "";
		$model->invoice_time = "";
	?>

		<h3>新增憑證</h3>

		<div id="form">
			<div class="form-group">
				<label><?php echo $form->labelEx($model,'invoice'); ?></label>
				<?php echo $form->textField($model,'invoice',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"")); ?>
				<p class="text-danger"><?php echo $form->error($model,'invoice'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'invoice_time'); ?></label>
				<?php echo $form->textField($model,'invoice_time',array('size'=>60,'maxlength'=>255 , "class"=>"form-control datepicker-readonly" , "placeholder"=>"", "readonly"=>"readonly")); ?>
				<p class="text-danger"><?php echo $form->error($model,'invoice_time'); ?></p>
			</div>
		</div>

	<?php }	?>
</div>


<div class="modal-footer">
	<?php if(!empty($model->invoice)){?>
		<button type="button" class="btn btn-danger reset-btn">重填</button>		
	<?php }else{ ?>
		<button type="button" class="btn btn-primary create-btn">新增</button>
	<?php }?>
	<button type="button" class="btn btn-default close-btn" data-dismiss="modal">關閉</button>
</div>
<?php $this->endWidget(); ?>