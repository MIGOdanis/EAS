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
	.belong-btn, .active-btn, .receivables-btn{
		width: 100%;
		margin-top: 5px;
	}
</style>
<h5>查詢時間 : <?php echo $day[0];?> ~ <?php echo $day[1];?></h5>
<?php 
if(isset($_GET['CampaignId']) && ($_GET['CampaignId'] > 0))
	echo '<h5>查詢訂單編號 :' . $_GET['CampaignId'] . '</h5>';

if(isset($creater) && $creater !== null )
	echo '<h5>查詢建單帳號 :' . $creater->real_name . '</h5>';

if(isset($_GET['active']) && ($_GET['active'] > 0))
	echo '<h5>查詢狀態 :' . (($_GET['active'] == 1) ? "已結案" : "未結案")  . '</h5>';
?>
<script type="text/javascript">
	var updateReports = 0;
	$('.set-btn').click(function() {
		var url = $(this).prop('href');
		$.ajax({
			url:url,
			success:function(html){
				$('#modal-content-lg').html(html);
				$('#modal-lg').modal('show');
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
		return false;//阻止a标签		
	});

	$('.belong-btn').click(function() {
		var url = $(this).prop('href');
		$.ajax({
			url:url,
			success:function(html){
				$('#modal-content').html(html);
				$('#modal').modal('show');
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
		return false;//阻止a标签		
	});

	$(".active-btn").click(function(){
		var url = $(this).prop('href');
		$.ajax({
			url:url,
			success:function(html){
				$('#modal-content').html(html);
				$('#modal').modal('show');
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
        return false;
	})

</script>

<?php
// Yii::app()->clientScript->registerScript('search', "
// 	$('.search-button, .sort-link').click(function(){
// 		$('.search-form').toggle();
// 		return false;
// 	});
// 	$('.search-form form').submit(function(){
// 		$('#yiiCGrid').yiiGridView('update', {
// 			data: $(this).serialize()
// 		});
// 		return false;
// 	});
// ");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'yiiCGrid',
	'itemsCssClass' => 'table table-bordered',
	'dataProvider'=>$allData = $model->advertiserAccountsReport(),
	'filter'=>$model,
	'ajaxUpdate'=>true,
	'afterAjaxUpdate'=>'tableEvent',	
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
			'name' => "campaign.advertiser.tos_id",
			'header' => "<div class='topItem'>統一編號</div>訂單編號",
			'type' => "raw",
			'value'=>'"<div class=\'topItem\'>" .  "-" ."</div>" . $data->campaign_id',
			// 'htmlOptions'=>array('width'=>'120'),
			'filter'=>false,
		),						
		array(
			'name' => "campaign_id",
			'header' => "<div class='topItem'>發票抬頭</div>訂單名稱",
			'type' => "raw",
			'value'=>'"<div class=\'topItem\'>" . $data->campaign->advertiser->advertiser_name ."</div>" . $data->campaign->campaign_name',
			'filter'=>false,
			// 'htmlOptions'=>array('width'=>'120'),
		),	
		array(
			'name' => "campaign.budget.total_budget",
			'header' => "訂單金額",
			'value'=>'"$".number_format(($data->budget->total_budget / 100), 0, "." ,",")',
			'filter'=>false,
			//'htmlOptions'=>array('width'=>'100'),
		),	
		array(
			'header' => "<div class='topItem'>目標曝光</div>目標點擊",
			'value'=>'"<div class=\'topItem\'>" . (($data->budget->total_pv > 0) ? number_format($data->budget->total_pv, 0, "." ,",") : "-") . "</div>" . (($data->budget->total_click > 0) ? number_format($data->budget->total_click, 0, "." ,",") : "-")',
			'filter'=>false,
			'type' => "raw",
			'htmlOptions'=>array('width'=>'65'),
		),		
		array(
			'header' => "<div class='topItem'>CPM</div>CPC",
			'value'=>'"<div class=\'topItem\'>" . (($data->budget->total_pv > 0) ? "$".number_format(($data->budget->total_budget / 100) / $data->budget->total_pv, 2, "." ,",") : "-") . "</div>" . (($data->budget->total_click > 0) ? "$".number_format(($data->budget->total_budget / 100) / $data->budget->total_click, 2, "." ,",") : "-")',
			'filter'=>false,
			'type' => "raw",
			'htmlOptions'=>array('width'=>'50'),
		),
		array(
			'name' => "campaign.start_time",
			'header' => "訂單走期",
			'type' => "raw",
			'value'=>'"<div class=\'topItem\'>" . date("Y-m-d", $data->campaign->start_time) ."</div>" . date("Y-m-d", $data->campaign->end_time)',
			'filter'=>false,
			// 'htmlOptions'=>array('width'=>'85'),
		),	
		array(
			'header' => "<div class='topItem'>查詢曝光</div>查詢點擊",
			'value'=>'"<div class=\'topItem\'>" . (($data->impression_sum > 0) ? number_format($data->impression_sum, 0, "." ,",") : "-") . "</div>" . (($data->click_sum > 0) ? number_format($data->click_sum, 0, "." ,",") : "-")',
			'filter'=>false,
			'type' => "raw",
			//'htmlOptions'=>array('width'=>'100'),
		),	
		array(
			'header' => "<div class='topItem'>查詢執<br>行金額</div>總執行",
			'value'=>'"<div class=\'topItem\'>" . "$".number_format($data->income_sum, 0, "." ,",") . "</div>" . "$".number_format($data->getCampaignAllIncome($data->campaign_id), 0, "." ,",")',
			'filter'=>false,
			'type' => "raw",
			//'htmlOptions'=>array('width'=>'100'),
		),		
		array(
			'header' => "<div class='topItem'>未執行</div>可請款",
			'type'=>'raw',
			'value'=>'"<div class=\'topItem\'>" . "$".number_format(($data->budget->total_budget / 100) - $data->temp_income_sum, 0, "." ,",") . "</div>" . "$".number_format(($data->temp_income_sum > ($data->budget->total_budget / 100))? ($data->budget->total_budget / 100) : $data->temp_income_sum   , 0, "." ,",")',
			'filter'=>false,
			// 'htmlOptions'=>array('width'=>'80'),
		),	
		array(
			'header' => "<div class='topItem'>已開<br>發票</div>未請款",
			'type'=>'raw',
			'value'=>'
				"<div class=\'topItem\'>" . 
					"$".number_format($data->getCampaignAdvertiserInvoice($data->campaign_id), 0, "." ,",") . 
				"</div>" . 
				"$".number_format(($data->temp_income_sum > ($data->budget->total_budget / 100))
					? ($data->budget->total_budget / 100) - $data->temp_advertiser_invoice_sum 
					: $data->temp_income_sum - $data->temp_advertiser_invoice_sum, 0, "." ,",")',
			'filter'=>false,
			//'htmlOptions'=>array('width'=>'100'),
		),
		array(
			'header'=>"<div class='topItem'>查詢期<br>間認列<br>金額</div>認列<br>作業",
			'type'=>'raw',
			'value'=> '"<div class=\'topItem\'>$" . number_format($data->getCampaignAdvertiserReceivables($data->campaign_id), 0, "." ,",") . "</div>" .CHtml::link("認列",array("advertiserAccounts/creatReceivables","id"=>$data->campaign_id),array("class"=>"btn btn-" . $data->temp_receivables_btn . " receivables-btn set-btn"))',
			'htmlOptions'=>array('width'=>'55')
		),		
		array(
			'header'=>'發票<br>作業',
			'type'=>'raw',
			'value'=> 'CHtml::link("發票",array("advertiserAccounts/creatInvoice","id"=>$data->campaign_id),array("class"=>"btn btn-default set-btn"))',
			'htmlOptions'=>array('width'=>'55')
		),
		array(
			'header'=>"<div class='topItem'>建單帳號</div>訂單業務",
			'type'=>'raw',
			'value'=>'"<div class=\'topItem\'>" . $data->campaign->upm->real_name  ."</div>" . CHtml::link((($data->campaign->belong_by > 0)? $data->campaign->belong->name : "未填寫"),array("advertiserAccounts/selectBelong","id"=>$data->campaign_id),array("class"=>"btn btn-default btn-xs belong-btn")) ',
			// 'htmlOptions'=>array('width'=>'55')
		),
		array(
			'header'=>'結案',
			'type'=>'raw',
			'value'=> '"<div class=\'topItem\'>" . (($data->campaign->active == 0)? "$".number_format($data->campaign->close_price, 0, "." ,",") : "-")  ."</div>" . CHtml::link((($data->campaign->active == 0)? "重啟" : "結案"),array("advertiserAccounts/selectActive","id"=>$data->campaign_id),array("class"=>"btn btn-default  btn-xs active-btn"))',
			'htmlOptions'=>array('width'=>'80')
		),			
	),
));



?>