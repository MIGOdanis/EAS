<?php $sunIncome = ($allIncome > ($campaign->budget->total_budget / 100)) ? ($campaign->budget->total_budget / 100) : $allIncome ;?>
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
</style>
<script type="text/javascript">
var sunIncome = <?php echo $sunIncome - $advertiserInvoice;?>;
var thisPage = "creatInvoice?id=<?php echo $_GET['id']; ?>";
$('#AdvertiserInvoice_time').datepicker({
	format: "yyyy/mm/dd",
    language: "zh-TW",
    todayHighlight: true,
     autoclose: true,
});
$('.save-btn').click(function(){
	var number = $("#AdvertiserInvoice_number").val();
	var price = $("#AdvertiserInvoice_price").val();
	var time = $("#AdvertiserInvoice_time").val();
	var remark = $("#AdvertiserInvoice_remark").val();
	if(number.length > 0 && price.length > 0 && time.length > 0){
			if(price > sunIncome){
				if(!confirm("發票金額超過未請款金額! 確認是否新增此發票?")){
					return false;
				}
			}

			if(confirm("請確認發票編號" + number + " 金額" + price)){
				$.ajax({
					url:thisPage ,
					type:"post",
					data:{ number : number, price : price, time : time , remark : remark, AdvertiserInvoice : 1},
					dataType:"json",
					success:function(data){
						if(data.code == 1){
							getReport();
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
	if(confirm("請確認是否註銷此發票?")){
		$.ajax({
			url:url,
			// type:"post",
			// data:{ number : number, price : price, time : time , AdvertiserInvoice : 1},
			dataType:"json",
			success:function(data){
				if(data.code == 1){
					getReport();
					alert("註銷完成");
				}else{
					alert("註銷失敗 #" + data.code);
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
	$.ajax({
		url:thisPage ,
		success:function(html){
			$('#modal-content-lg').html(html);
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

</script>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel">已開發票總覽</h4>
</div>
<div class="modal-body">	
	<h3>訂單資訊</h3>
	<h5>訂單編號 : <?php echo $campaign->tos_id; ?></h5>
	<h5>訂單名稱 : <?php echo $campaign->campaign_name; ?></h5>
	<h5>訂單金額 : <?php echo "$".number_format(($campaign->budget->total_budget / 100), 0, "." ,","); ?></h5>
	<h5>訂單已執行金額 : <?php echo "$".number_format($allIncome, 0, "." ,","); ?></h5>
	<h5>訂單可請款金額 : <?php echo "$".number_format( $sunIncome , 0, "." ,","); ?></h5>
	<h5>訂單已開發票金額 : <?php echo "$".number_format($advertiserInvoice, 0, "." ,","); ?></h5>
	<h5>訂單未請款金額 : <?php echo "$".number_format($sunIncome - $advertiserInvoice, 0, "." ,","); ?></h5>

	<h3>已開發票</h3>
	<?php		
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
				'header' => "<div class='topItem'>訂單編號</div>訂單名稱",
				'type' => "raw",
				'value'=>'"<div class=\'topItem\'>" . $data->campaign_id . "</div>" . $data->campaign->campaign_name',
				// 'htmlOptions'=>array('width'=>'150'),
				'filter'=>false,
			),
			array(	
				'header' => "<div class='topItem'>發票請款金額</div>發票號碼",
				'type' => "raw",
				'value'=>'"<div class=\'topItem\'>" . "$".number_format($data->price, 0, "." ,",") . "</div>" . $data->number',
				// 'htmlOptions'=>array('width'=>'150'),
				'filter'=>false,
			),
			array(	
				'header' => "發票日期",
				'value'=>'date("Y-m-d",$data->time)',
				// 'htmlOptions'=>array('width'=>'150'),
				'filter'=>false,
			),			
			array(	
				'header' => "填寫者",
				'value'=>'$data->invoiceCreater->name',
				// 'htmlOptions'=>array('width'=>'150'),
				'filter'=>false,
			),				
			array(
				'header' => "狀態",
				'value'=>'($data->active == 0)? "註銷" : "有效"',
				// 'htmlOptions'=>array('width'=>'90'),
				'filter'=>false,
			),	
			array(
				'header'=>'註銷',
				'type'=>'raw',
				'value'=> '($data->active == 1)? CHtml::link("註銷",array("advertiserAccounts/delInvoice","id"=>$data->id),array("class"=>"btn btn-default del-btn")) : "註銷"',
				'htmlOptions'=>array('width'=>'55')
			),				
			array(	
				'header' => "備註",
				'value'=>'$data->remark',
				// 'htmlOptions'=>array('width'=>'150'),
				'filter'=>false,
			),															
		),
	));?>

	<h3>新增發票</h3>
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'site-setting-form',
		'enableAjaxValidation'=>true,
		'htmlOptions' => array('enctype' => 'multipart/form-data'),
	)); ?>
	<div id="form">
		<div class="form-group">
			<label><?php echo $form->labelEx($model,'number'); ?></label>
			<?php echo $form->textField($model,'number',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"")); ?>
			<p class="text-danger"><?php echo $form->error($model,'number'); ?></p>
		</div>

		<div class="form-group">
			<label><?php echo $form->labelEx($model,'price'); ?></label>
			<?php echo $form->textField($model,'price',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"")); ?>
			<p class="text-danger"><?php echo $form->error($model,'price'); ?></p>
		</div>

		<div class="form-group">
			<label><?php echo $form->labelEx($model,'time'); ?></label>
			<?php echo $form->textField($model,'time',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"")); ?>
			<p class="text-danger"><?php echo $form->error($model,'time'); ?></p>
		</div>

		<div class="form-group">
			<label><?php echo $form->labelEx($model,'remark'); ?></label>
			<?php echo $form->textField($model,'remark',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"")); ?>
			<p class="text-danger"><?php echo $form->error($model,'remark'); ?></p>
		</div>

	</div>
</div>
<div class="modal-footer">
	<?php echo CHtml::submitButton('新增',array('class' => 'btn btn-primary save-btn')); ?>
	<button type="button" class="btn btn-default close-btn" data-dismiss="modal">關閉</button>
</div>
<?php $this->endWidget(); ?>