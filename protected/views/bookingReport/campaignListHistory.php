<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/locales/bootstrap-datepicker.zh-TW.min.js" charset="UTF-8"></script>
<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/css/advertiserReport.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/js/advertiserReport.js" charset="UTF-8"></script>
<div id="supplier-report">
	<h3>BOOKING</h3>
	<style type="text/css">
		#yiiCGrid{
			text-align: left;
		}
		table{
			border: solid 1px #ACACAC;
			font-size: 12px;
		}	
		.click{
			background-color: #FF988A;
			color: #fff;
			text-align: center;
		}
		.imp{
			background-color: #75AF75;
			color: #fff;
			text-align: center;
		}
		.budget{
			background-color: #4696AA;
			color: #fff;
			text-align: center;			
		}		
	/*	th{
			background-color: #DCDCDC;
		}*/

	</style>
	<script type="text/javascript">
		$(function(){
			$("#filter-btn").click(function(){
				$.ajax({
						url:"filterCampaign",
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
			});
		})
	</script>
	<div class="btn-group" role="group" aria-label="...">
		<a href="campaignListHistory?day=<?php echo $_GET['day'];?>" class="btn btn-default">全部</a>
		<a href="campaignListHistory?type=1&day=<?php echo $_GET['day'];?>" class="btn btn-default">只看PC</a>
		<a href="campaignListHistory?type=2&day=<?php echo $_GET['day'];?>" class="btn btn-default">只看MOB</a>
		<button type="button" class="btn btn-default" id="filter-btn">
		  	<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> 訂單濾除
		</button>
	</div>

	<?php
	Yii::app()->clientScript->registerScript('search', "
		$('.search-button, .sort-link').click(function(){
			$('.search-form').toggle();
			return false;
		});
		$('.search-form form').submit(function(){
			$('#yiiCGrid').yiiGridView('update', {
				data: $(this).serialize()
			});
			return false;
		});
	");

	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'yiiCGrid',
		'itemsCssClass' => 'table table-bordered',
		'dataProvider'=>$allData = $model->campaignList(),
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
				'name' => "campaign.id",
				'header' => "訂單編號",
				'value'=>'$data->campaign_id',
				// 'htmlOptions'=>array('width'=>'100','class'=>'day'),
				'filter'=>false,
			),		
			array(	
				'name' => "campaign.id",
				'header' => "訂單",
				'value'=>'$data->campaign->campaign_name',
				// 'htmlOptions'=>array('width'=>'100','class'=>'day'),
				'filter'=>false,
			),
			array(	
				'name' => "strategy_id",
				'header' => "策略編號",
				'value'=>'$data->strategy_id',
				// 'htmlOptions'=>array('width'=>'100','class'=>'day'),
				'filter'=>false,
			),		
			array(	
				'name' => "strategy_id",
				'header' => "策略",
				'value'=>'$data->strategy->strategy_name',
				// 'htmlOptions'=>array('width'=>'100','class'=>'day'),
				'filter'=>false,
			),	
			// array(	
			// 	'name' => "booking_day",
			// 	'header' => "走期(D)",
			// 	'value'=>'$data->booking_day',
			// 	// 'htmlOptions'=>array('width'=>'100','class'=>'day'),
			// 	'filter'=>false,
			// ),			
			// array(	
			// 	'name' => "remaining_day",
			// 	'header' => "剩餘走期(D)",
			// 	'value'=>'$data->remaining_day',
			// 	// 'htmlOptions'=>array('width'=>'100','class'=>'day'),
			// 	'filter'=>false,
			// ),							
			array(	
				'name' => "day_click",
				'header' => "當日點擊預估",
				'value'=>'number_format($data->day_click, 0, "." ,",")',
				'htmlOptions'=>array('class'=>'click'),
				'filter'=>false,
			),
			array(	
				'name' => "run_click",
				'header' => "實際實行點擊",
				'value'=>'number_format($data->run_click, 0, "." ,",")',
				'htmlOptions'=>array('class'=>'click'),
				'filter'=>false,
			),	
			array(	
				'header' => "未執行點擊",
				'value'=>'number_format(($data->day_click - $data->run_click), 0, "." ,",")',
				'htmlOptions'=>array('class'=>'click'),
				'filter'=>false,
			),	
			array(	
				'header' => "點擊執行率",
				'value'=>'number_format((($data->day_click > 0) ? ($data->run_click / $data->day_click) * 100 : 0), 2, "." ,",") . "%"',
				'htmlOptions'=>array('class'=>'click'),
				'filter'=>false,
			),								
			array(	
				'name' => "day_imp",
				'header' => "日曝光預估",
				'value'=>'number_format($data->day_imp, 0, "." ,",")',
				'htmlOptions'=>array('class'=>'imp'),
				'filter'=>false,
			),
			array(	
				'name' => "run_imp",
				'header' => "實際實行曝光",
				'value'=>'number_format($data->run_imp, 0, "." ,",")',
				'htmlOptions'=>array('class'=>'imp'),
				'filter'=>false,
			),
			array(	
				'header' => "未執行曝光",
				'value'=>'number_format(($data->day_imp - $data->run_imp), 0, "." ,",")',
				'htmlOptions'=>array('class'=>'imp'),
				'filter'=>false,
			),	
			array(	
				'header' => "曝光執行率",
				'value'=>'number_format((($data->day_imp > 0) ? ($data->run_imp / $data->day_imp) * 100 : 0), 2, "." ,",") . "%"',
				'htmlOptions'=>array('class'=>'imp'),
				'filter'=>false,
			),							
			array(	
				'name' => "day_budget",
				'header' => "日預算預估",
				'value'=>'number_format($data->day_budget, 0, "." ,",")',
				'htmlOptions'=>array('class'=>'budget'),
				'filter'=>false,
			),	
			array(	
				'name' => "run_budget",
				'header' => "實際實行預算",
				'value'=>'number_format($data->run_budget, 0, "." ,",")',
				'htmlOptions'=>array('class'=>'budget'),
				'filter'=>false,
			),	
			array(	
				'header' => "未執行預算",
				'value'=>'number_format(($data->day_budget - $data->run_budget), 0, "." ,",")',
				'htmlOptions'=>array('class'=>'budget'),
				'filter'=>false,
			),	
			array(	
				'header' => "預算執行率",
				'value'=>'number_format((($data->day_budget > 0) ? ($data->run_budget / $data->day_budget) * 100 : 0), 2, "." ,",") . "%"',
				'htmlOptions'=>array('class'=>'budget'),
				'filter'=>false,
			),						
		),
	));

	?>
</div>