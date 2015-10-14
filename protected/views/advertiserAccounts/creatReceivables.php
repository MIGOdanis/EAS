<style type="text/css">
	#yiiCGrid{
		text-align: left;
	}
	table{
		border: solid 1px #ACACAC;
		/*word-break: break-all;*/
		font-size: 14px;
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
getDefind();
var sunIncome = <?php echo ($campaign->budget->total_budget / 100) - $advertiserReceivables;?>;
var thisPage = "creatReceivables?id=<?php echo $_GET['id']; ?>";



$('#AdvertiserReceivables_time').datepicker({
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
	var price = $("#AdvertiserReceivables_price").val();
	var remark = $("#AdvertiserReceivables_remark").val();
	var month = $("#month").val();
	var year = $("#year").val();
	var checkSession = true;
	var errMsg = "請確認資料已填寫";
	$(".session").each(function(){
		var text = $(this).html();
		if(text == year+"-"+month){
			errMsg = "本月份已認列，請先註銷";
			checkSession = false;
		}
	});	

	if(price.length > 0 && checkSession){
			if(price > sunIncome){
				if(!confirm("認列款項金額超過未請款金額! 確認是否新增此認列款項?")){
					return false;
				}
			}

			if(confirm("請確認認列" + year + "年度" + month + "月份 額度" + price)){
				$.ajax({
					url:thisPage ,
					type:"post",
					data:{ price : price, year : year , month : month, remark : remark, AdvertiserReceivables : 1},
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
		alert(errMsg);
	}
});	
$('.del-btn').click(function(){
	var url = $(this).prop("href");
	if(confirm("請確認是否註銷此認列款項?")){
		$.ajax({
			url:url,
			// type:"post",
			// data:{ number : number, price : price, time : time , AdvertiserReceivables : 1},
			dataType:"json",
			success:function(data){
				if(data.code == 1){
					updateReports = 1;
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

//要做延遲
function refresh(){
	$('#yiiCGrid-AI').html("更新中...");
	setTimeout(function(){
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
	}, 500);
}

$("#month").change(function(){
	getDefind()
});

$("#year").change(function(){
	getDefind()
});

function getDefind(){
	var month = $("#month").val();
	var year = $("#year").val();	
	$.ajax({
		url:"getDefindReceivables",
		type:"get",
		data:{ CampaignId : "<?php echo $campaign->tos_id; ?>", Y : year , M : month},
		dataType:"json",
		success:function(data){
			if(data.income == "null"){
				$("#AdvertiserReceivables_price").val(0);
			}else{
				$("#AdvertiserReceivables_price").val(Math.round(data.income));
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
}
</script>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel">已開認列款項總覽</h4>
</div>
<div class="modal-body">	
	<h3>訂單資訊</h3>
	<h5>訂單編號 : <?php echo $campaign->tos_id; ?></h5>
	<h5>訂單名稱 : <?php echo $campaign->campaign_name; ?></h5>
	<h5>訂單金額 : <?php echo "$".number_format(($campaign->budget->total_budget / 100), 0, "." ,","); ?></h5>
	<h5>訂單走期 : <?php echo date("Y-m-d", $campaign->start_time) . "~" . date("Y-m-d", $campaign->end_time); ?></h5>
	<h5>訂單已認列金額 : <?php echo "$".number_format($advertiserReceivables, 0, "." ,","); ?></h5>
	<h5>訂單未認列金額 : <?php echo "$".number_format(($campaign->budget->total_budget / 100) - $advertiserReceivables, 0, "." ,","); ?></h5>	
	<h5>訂單已執行金額 : <?php echo "$".number_format($allIncome, 0, "." ,","); ?></h5>
	<h5>訂單可請款金額 : <?php echo "$".number_format( $sunIncome , 0, "." ,","); ?></h5>

	<h3>已開認列款項</h3>
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
				'header' => "認列期間",
				'value'=>'$data->year . "-" . $data->month',
				'htmlOptions'=>array('class'=>'session'),
				'filter'=>false,
			),	
			array(	
				'header' => "認列額度",
				'value'=>'$data->price',
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
				'value'=> '($data->active == 1)? CHtml::link("註銷",array("advertiserAccounts/delReceivables","id"=>$data->id),array("class"=>"btn btn-default del-btn")) : "註銷"',
				'htmlOptions'=>array('width'=>'55')
			),
			array(	
				'header' => "填寫者",
				'value'=>'$data->receivablesCreater->name',
				// 'htmlOptions'=>array('width'=>'150'),
				'filter'=>false,
			),					
			array(	
				'header' => "操作時間",
				'value'=>'date("Y-m-d H:i",$data->create_time)',
				// 'htmlOptions'=>array('width'=>'150'),
				'filter'=>false,
			),							
			array(	
				'header' => "備註",
				'value'=>'$data->remark',
				// 'htmlOptions'=>array('width'=>'150'),
				'filter'=>false,
			),															
		),
	));?>

	<h3>新增認列款項</h3>
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'site-setting-form',
		'enableAjaxValidation'=>true,
		'htmlOptions' => array('enctype' => 'multipart/form-data'),
	)); ?>
	<div id="form">
		<label>認列期間</label>
		<div class="form-group">
		<select name="AdvertiserReceivables[year]" class="select-type" id="year">
		<?php for($y=2015; $y <= date("Y"); $y++) {?>
			<option value="<?php echo $y?>" 
				<?php if((isset($_GET['year']) && $_GET['year'] == $y) || (!isset($_GET['year']) && date("Y") == $y)){ ?>
					selected="selected"<?php }?>>
				<?php echo $y?>
			</option>
		<?php }?>
		</select>年度
		<select name="AdvertiserReceivables[month]" class="select-type" id="month">
		<?php for($m=1; $m <= 12; $m++) {?>
			<option value="<?php echo $m?>" 
				<?php if((isset($_GET['month']) && $_GET['month'] == $m) || (!isset($_GET['month']) && date("m",strtotime("-1 month")) == $m)){ ?>
					selected="selected"<?php }?>>
				<?php echo $m?>
			</option>
		<?php }?>
		</select>月份
		</div>

		<div class="form-group">
			<label><?php echo $form->labelEx($model,'price'); ?></label>
			<?php echo $form->textField($model,'price',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"")); ?>
			<p class="text-danger"><?php echo $form->error($model,'price'); ?></p>
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