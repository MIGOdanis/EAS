<style type="text/css">
	#yiiCGrid{
		text-align: left;
	}
	table{
		border: solid 1px #ACACAC;
		/*word-break: break-all;*/
		font-size: 12px;
	}	
	.day{
		font-weight: bold;
	}
	.topItem{
		border-bottom: solid 1px;
	}
/*	th{
		background-color: #DCDCDC;
	}*/
	.belong-btn, .active-btn{
		width: 100%;
		margin-top: 5px;
	}
	.filters{
		display: none;
	}
</style>
<script type="text/javascript">
var sunIncome = <?php echo $sunIncome - $advertiserInvoice;?>;
var thisPage = "deduct?id=<?php echo $_GET['id']; ?>";
$('#DeductAccounts_date').datepicker({
	format: "yyyy/mm/dd",
    language: "zh-TW",
    todayHighlight: true,
     autoclose: true,
});

$('.close-btn').click(function(){
	if(updateReports){
		updateReports = 0;
		getReport();
	}
});

$('.save-btn').click(function(){
	var reson = $("#DeductAccounts_reson").val();
	var deduct = $("#DeductAccounts_deduct").val();
	var date = $("#DeductAccounts_date").val();
	if(reson.length > 0 && deduct.length > 0 && date.length > 0){
			// if(deduct > sunIncome){
			// 	if(!confirm("發票金額超過未請款金額! 確認是否新增此發票?")){
			// 		return false;
			// 	}
			// }

			if(confirm("請確認扣款原因" + reson + " 金額" + deduct)){
				$.ajax({
					url:thisPage ,
					type:"post",
					data:{ reson : reson, deduct : deduct, date : date , DeductAccounts : 1},
					dataType:"json",
					success:function(data){
						if(data.code == 1){
							updateReports = 1;
							alert("新增完成");
						}else{
							alert("新增失敗");
						}					
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
		        refresh();
			}
			
	}else{
		alert("請確認資料已填寫");
	}
});	
$('.del-btn').click(function(){
	var url = $(this).prop("href");
	if(confirm("請確認是否取消此扣款?")){
		$.ajax({
			url:url,
			dataType:"json",
			success:function(data){
				if(data.code == 1){
					updateReports = 1;
					alert("取消完成");
				}else{
					alert("取消失敗 #" + data.code);
				}					
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
	    refresh();
	}
	
	return false;
});	

function refresh(){
	$('#yiiCGrid-AI').html("更新中...");
	setTimeout(function(){
		$.ajax({
			url:thisPage ,
			success:function(html){
				$('#modal-content-lg').html(html);
				$('#yiiCGrid').yiiGridView('update');
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
	}, 500);
}

</script>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel">扣款明細</h4>
</div>
<div class="modal-body">	


	<h3>已扣款項</h3>
	<?php	
	function transStatus($data){
		$status = array("已扣款","待扣款","已取消");
		return $status[$data->status];
	}	
	Yii::app()->clientScript->registerScript('search', "
		$('.search-button, .sort-link').click(function(){
			$('.search-form').toggle();
			return false;
		});
		$('.search-form form').submit(function(){
			$('#yiiCGrid-AI').yiiGridView('update', {
				data: $(this).serialize()
			});
			return false;
		});
	");
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'yiiCGrid-AI',
		'itemsCssClass' => 'table table-bordered',
		'dataProvider'=>$model->search($_GET['id']),
		'filter'=>$model,
		'summaryText'=>'共 {count} 筆資料，目前顯示第 {start} 至 {end} 筆',
		'emptyText'=>'沒有資料',
		'pager' => array(
			'nextPageLabel' => '»',
			'prevPageLabel' => '«',
			'firstPageLabel' => ' ',
			'lastPageLabel'=> ' ',
			'header' => ' ',
			'htmlOptions' => array('class'=>'pagination'),
			'hiddenPageCssClass' => '',
			'selectedPageCssClass' => 'active',
			'previousPageCssClass' => '',
			'nextPageCssClass' => ''
		),
		'template'=>'{pager}{items}{pager}',
		'columns'=>array(				
			array(	
				'header' => "扣款說明",
				'type' => "raw",
				'value'=>'$data->reson',
				'filter'=>false,
			),
			array(	
				'header' => "扣款金額",
				'type' => "raw",
				'value'=>'"$".number_format($data->deduct, 0, "." ,",")',
				'filter'=>false,
				'htmlOptions'=>array('width'=>'130'),
			),
			array(	
				'header' => "扣款日期",
				'value'=>'date("Y-m-d",$data->date)',
				'filter'=>false,
				'htmlOptions'=>array('width'=>'100'),
			),			
			array(	
				'header' => "填寫者",
				'value'=>'$data->creater->name',
				'filter'=>false,
				'htmlOptions'=>array('width'=>'100'),
			),				
			array(
				'header' => "狀態",
				'value'=>'transStatus($data)',
				'htmlOptions'=>array('width'=>'60'),
				'filter'=>false,
			),	
			array(
				'header'=>'申請日期',
				'type'=>'raw',
				'value'=> '($data->status == 0)? $data->application_year ."-" . $data->application_month  : "-"',
				'htmlOptions'=>array('width'=>'100')
			),

			array(
				'header'=>'註銷',
				'type'=>'raw',
				'value'=> '($data->status == 1)? CHtml::link("取消",array("supplierApplicationMonies/delDeduct","id"=>$data->id),array("class"=>"btn btn-default del-btn")) : "-"',
				'htmlOptions'=>array('width'=>'55')
			)															
		),
	));
	?>

	<h3>扣除款項</h3>
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'site-setting-form',
		'enableAjaxValidation'=>true,
		'htmlOptions' => array('enctype' => 'multipart/form-data'),
	)); ?>
	<div id="form">
		<div class="form-group">
			<label><?php echo $form->labelEx($model,'reson'); ?></label>
			<?php echo $form->textField($model,'reson',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"")); ?>
			<p class="text-danger"><?php echo $form->error($model,'reson'); ?></p>
		</div>

		<div class="form-group">
			<label><?php echo $form->labelEx($model,'deduct'); ?></label>
			<?php echo $form->textField($model,'deduct',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"")); ?>
			<p class="text-danger"><?php echo $form->error($model,'deduct'); ?></p>
		</div>

		<div class="form-group">
			<label><?php echo $form->labelEx($model,'date'); ?></label>
			<?php echo $form->textField($model,'date',array('size'=>60,'maxlength'=>255 , "class"=>"form-control datepicker-readonly" , "placeholder"=>"", "readonly"=>"readonly")); ?>
			<p class="text-danger"><?php echo $form->error($model,'date'); ?></p>
		</div>
	</div>
</div>
<div class="modal-footer">
	<?php echo CHtml::submitButton('新增',array('class' => 'btn btn-primary save-btn')); ?>
	<button type="button" class="btn btn-default close-btn" data-dismiss="modal">關閉</button>
</div>
<?php $this->endWidget(); ?>