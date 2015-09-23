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
		tfoot{
			text-align: center;
			font-weight: bold;
		}
		.click{
			background-color: #FF988A;
			color: #fff;
			text-align: center;
			font-size: 13px;
			line-height: 52px;
			padding-top: 15px !important;
		}
		.imp{
			background-color: #75AF75;
			color: #fff;
			text-align: center;
			font-size: 13px;
			line-height: 52px;
			padding-top: 15px !important;
		}
		.budget{
			background-color: #4696AA;
			color: #fff;
			text-align: center;	
			font-size: 13px;		
			line-height: 52px;
			padding-top: 15px !important;
		}	
		#content-singe{
			width: 100%;
			overflow-x:auto; 
		}				
		.topItem{
			border-bottom: solid 1px;
		}
		.st0{
			width: 10px;
			height: 10px;
			background-color: #FFC928;
			border-radius: 45px;
			position: absolute;
			right: 0px;
			top: -10px;
		}
		.st2{
			width: 10px;
			height: 10px;
			background-color: #A3D8F0;
			border-radius: 45px;
			position: absolute;
			right: 0px;
			top: -10px;
		}
		.st3{
			width: 10px;
			height: 10px;
			/*background-color: #A3D8F0;*/
			border-radius: 45px;
			border:3px solid #A3D8F0;
			position: absolute;
			right: 0px;
			top: -10px;
		}	
		.st4{
			width: 10px;
			height: 10px;
			/*background-color: #A3D8F0;*/
			border-radius: 45px;
			border:1px solid #A3D8F0;
			position: absolute;
			right: 0px;
			top: -10px;
		}	
		.st-tag-0{
			color: #EBB81D;
		}
		.st-tag-2{
			color: #6DC5EE;
		}
		.report-txt{
			width: 100%;
			height: 100%;
			position: relative;

		}	
	</style>
	<script type="text/javascript">
		$(function(){
			$(".filter-btn").click(function(){
				var url = $(this).data("url");
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
			});
		})
	</script>
	<div class="btn-group" role="group" aria-label="...">
		<a href="campaignListHistory?day=<?php echo $_GET['day'];?>" class="btn btn-default">全部</a>
		<a href="campaignListHistory?type=1&day=<?php echo $_GET['day'];?>" class="btn btn-default">只看PC</a>
		<a href="campaignListHistory?type=2&day=<?php echo $_GET['day'];?>" class="btn btn-default">只看MOB</a>
		<button type="button" class="btn btn-default filter-btn" data-url="filterCampaign">
		  	<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> 訂單濾除
		</button>
		<button type="button" class="btn btn-default filter-btn" data-url="filterDate">
			<span class="glyphicon glyphicon-th"  aria-hidden="true"></span> 查詢日期
		</button>
	</div>
	<div>查詢日期 : <?php echo $_GET['day'];?></div>
	<div>
		<?php
		if(isset($_GET['type']) && $_GET['type'] > 0){
			echo ($_GET['type'] == 1)? "篩選 : 只看PC" : "篩選 : 只看MOB";
		}
		?>

	</div>
	<div>演算法版本 : 0.1b</div>	
	<div><span class="glyphicon glyphicon-tag st-tag-2" aria-hidden="true"></span>此顏色標記為依據<span class="st-tag-2">訂單</span>限制計算的預估值</div>
	<div><span class="glyphicon glyphicon-tag st-tag-0" aria-hidden="true"></span>此顏色標記為依據<span class="st-tag-0">策略</span>限制計算的預估值</div>
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
				'header' => "<div class='topItem'>訂單編號</div>策略編號",
				'type' => "raw",
				'value'=>'"<div class=\'topItem\'>" . CHtml::link($data->campaign_id,array("bookingReport/campaign","id"=>$data->campaign_id),array("target"=>"_blank")) . "</div>" . $data->strategy_id',
				// 'htmlOptions'=>array('width'=>'100','class'=>'day'),
				'filter'=>false,
				'footer'=>'總計'
			),		
			array(	
				'name' => "campaign.id",
				'header' => "<div class='topItem'>訂單</div>(優先級)策略名稱",
				'type' => "raw",
				'value'=>'"<div class=\'topItem\'>" . $data->campaign->campaign_name . "</div>" . " ( " . $data->strategy->priority  . " ) " . $data->strategy->strategy_name',
				// 'htmlOptions'=>array('width'=>'100','class'=>'day'),
				'filter'=>false,
			),						
			array(	
				'name' => "day_click",
				'header' => "當日點擊<br>預估",
				'type' => "raw",
				'value'=>'"<div class=\'report-txt\'>" . number_format($data->day_click, 0, "." ,",") . ( ($data->click_status == 1)? "" : "<div class=\'st" . $data->click_status . "\'></div>") . "</div>"',
				'htmlOptions'=>array('class'=>'click'),
				'filter'=>false,
				'footer'=>number_format($day_click = $model->sumColumn($allData,"day_click"), 0, "." ,","),
			),
			array(	
				'name' => "run_click",
				'header' => "實際實行<br>點擊",
				'value'=>'number_format($data->run_click, 0, "." ,",")',
				'htmlOptions'=>array('class'=>'click'),
				'filter'=>false,
				'footer'=>number_format($run_click = $model->sumColumn($allData,"run_click"), 0, "." ,","),
			),	
			array(	
				'header' => "未執行<br>點擊",
				'value'=>'number_format(($data->run_click - $data->day_click), 0, "." ,",")',
				'htmlOptions'=>array('class'=>'click'),
				'filter'=>false,
				'footer'=>number_format( ($run_click - $day_click) , 0, "." ,","),
			),	
			array(	
				'header' => "點擊<br>執行率",
				'value'=>'number_format((($data->day_click > 0) ? ($data->run_click / $data->day_click) * 100 : 0), 2, "." ,",") . "%"',
				'htmlOptions'=>array('class'=>'click'),
				'filter'=>false,
				'footer'=>number_format( (($day_click > 0) ? ($run_click / $day_click) * 100 : 0) , 2, "." ,",") . "%",
			),								
			array(	
				'name' => "day_imp",
				'header' => "日曝光<br>預估",
				'type' => "raw",
				'value'=>'"<div class=\'report-txt\'>" . number_format($data->day_imp, 0, "." ,",") . ( ($data->imp_status == 1)? "" : "<div class=\'st" . $data->imp_status . "\'></div>") . "</div>"',
				'htmlOptions'=>array('class'=>'imp'),
				'filter'=>false,
				'footer'=>number_format($day_imp = $model->sumColumn($allData,"day_imp"), 0, "." ,","),
			),
			array(	
				'name' => "run_imp",
				'header' => "實際實行<br>曝光",
				'value'=>'number_format($data->run_imp, 0, "." ,",")',
				'htmlOptions'=>array('class'=>'imp'),
				'filter'=>false,
				'footer'=>number_format($run_imp = $model->sumColumn($allData,"run_imp"), 0, "." ,","),
			),
			array(	
				'header' => "未執行<br>曝光",
				'value'=>'number_format(($data->run_imp - $data->day_imp), 0, "." ,",")',
				'htmlOptions'=>array('class'=>'imp'),
				'filter'=>false,
				'footer'=>number_format( ($run_imp - $day_imp) , 0, "." ,","),
			),	
			array(	
				'header' => "曝光<br>執行率",
				'value'=>'number_format((($data->day_imp > 0) ? ($data->run_imp / $data->day_imp) * 100 : 0), 2, "." ,",") . "%"',
				'htmlOptions'=>array('class'=>'imp'),
				'filter'=>false,
				'footer'=>number_format( (($day_imp > 0) ? ($run_imp / $day_imp) * 100 : 0) , 2, "." ,",") . "%",
			),							
			array(	
				'name' => "day_budget",
				'header' => "日預算<br>預估",
				'type' => "raw",
				'value'=>'"<div class=\'report-txt\'>" . number_format($data->day_budget, 0, "." ,",") . ( ($data->budget_status == 1)? "" : "<div class=\'st" . $data->budget_status . "\'></div>") . "</div>"',
				'htmlOptions'=>array('class'=>'budget'),
				'filter'=>false,
				'footer'=>number_format($day_budget = $model->sumColumn($allData,"day_budget"), 0, "." ,","),
			),	
			array(	
				'name' => "run_budget",
				'header' => "實際實行<br>預算",
				'value'=>'number_format($data->run_budget, 0, "." ,",")',
				'htmlOptions'=>array('class'=>'budget'),
				'filter'=>false,
				'footer'=>number_format($run_budget = $model->sumColumn($allData,"run_budget"), 0, "." ,","),
			),	
			array(	
				'header' => "未執行<br>預算",
				'value'=>'number_format(($data->run_budget - $data->day_budget), 0, "." ,",")',
				'htmlOptions'=>array('class'=>'budget'),
				'filter'=>false,
				'footer'=>number_format( ($run_budget  - $day_budget) , 0, "." ,","),
			),	
			array(	
				'header' => "預算<br>執行率",
				'value'=>'number_format((($data->day_budget > 0) ? ($data->run_budget / $data->day_budget) * 100 : 0), 2, "." ,",") . "%"',
				'htmlOptions'=>array('class'=>'budget'),
				'filter'=>false,
				'footer'=>number_format( (($day_budget > 0) ? ($run_budget / $day_budget) * 100 : 0) , 2, "." ,",") . "%",

			),						
		),
	));

	?>
</div>  