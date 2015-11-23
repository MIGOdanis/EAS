<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/locales/bootstrap-datepicker.zh-TW.min.js" charset="UTF-8"></script>
<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/css/advertiserReport.css" rel="stylesheet">
<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/css/booking.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/js/advertiserReport.js" charset="UTF-8"></script>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>
<div id="supplier-report">
	<h3>CAMPAIGN BOOKING</h3>
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

			$(document).keydown(function(e) {
				if(e.keyCode == 39){
					$("#content-singe").scrollLeft($("#content-singe").scrollLeft()+50);
				}	
				if(e.keyCode == 37){
					$("#content-singe").scrollLeft($("#content-singe").scrollLeft()-50);
				}				
			});			
		})
	</script>
	<div id="chart_div" style="width:100%; height:600px; display:none;"></div>
	<div class="btn-group" role="group" aria-label="...">
		<a href="campaign?id=<?php echo $_GET['id'];?>" class="btn btn-default">全部</a>
		<a href="campaign?type=1&id=<?php echo $_GET['id'];?>" class="btn btn-default">只看PC</a>
		<a href="campaign?type=2&id=<?php echo $_GET['id'];?>" class="btn btn-default">只看MOB</a>
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
	<div>
		<?php
		if($campaign !== null){
			echo "訂單 : (" . $campaign->tos_id . ") " .  $campaign->campaign_name;
		}
		?>
	</div>	

	<p>
	<div><span class="glyphicon glyphicon-tag" aria-hidden="true"></span>使用鍵盤左右鍵可以移動下表</div>		
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
		'dataProvider'=>$allData = $model->campaign(),
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
				'name' => "booking_time",
				'header' => "日期",
				'type' => "raw",
				'value'=>'date("Y-m-d", $data->booking_time)',
				// 'htmlOptions'=>array('width'=>'100','class'=>'day'),
				'filter'=>false,
				'footer'=>'總計'
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
			array(	
				'header' => "eCPC",
				'value'=>'number_format((($data->run_click <= 0) ? $data->run_budget : $data->run_budget / $data->run_click), 2, "." ,",")',
				'htmlOptions'=>array('class'=>'ecpc'),
				'filter'=>false,
				'footer'=>number_format((($run_click <= 0) ? $run_budget : $run_budget / $run_click), 2, "." ,","),
			),	
			array(	
				'header' => "eCPM",
				'value'=>'number_format((($data->run_imp <= 0) ? $data->run_budget : ($data->run_budget / $data->run_imp) * 1000), 2, "." ,",")',
				'htmlOptions'=>array('class'=>'ecpm'),
				'filter'=>false,
				'footer'=>number_format((($run_imp <= 0) ? $run_budget : ($run_budget / $run_imp) * 1000), 2, "." ,","),
			),									
		),
	));

	?>
</div>  
<?php 
if($allData !== null):
	$chartData = $model->getCampaignChartDate($allData);
	if(count($chartData) > 1):
?>
	<script type="text/javascript">
	// var chartData = <?php echo json_encode($chartData);?>;
	    // chartData = JSON.parse(chartData);
	 google.setOnLoadCallback(drawVisualization);

	      function drawVisualization() {
	        // Some raw data (not necessarily accurate)
	        var data = google.visualization.arrayToDataTable([
	        <?php foreach ($chartData as $value) {?>
	        	[ <?php echo implode(",",$value) ?> ],
	        <?php }?>
	        ]);

		    var options = {
		    	
				title : '訂單走勢圖',
				vAxis: { title: "曝光(右) 花費與點擊(左)" },
				hAxis: {title: '日期'},
				seriesType: 'bars',
				series: {
					0: {color: '#FF988A'},
					1: {targetAxisIndex:1,color: '#75AF75'},
					2: {color: '#4696AA'},
					3: {type: 'line',color: '#FF988A'}, 
					4: {type: 'line',targetAxisIndex:1,color: '#75AF75'}, 
					5: {type: 'line',color: '#4696AA'}
				}
		    };

	    var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
	    chart.draw(data, options);
	  }
	  $("#chart_div").show();
	</script>
<?php
	endif;
endif;?>