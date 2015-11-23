<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/locales/bootstrap-datepicker.zh-TW.min.js" charset="UTF-8"></script>
<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/css/advertiserReport.css" rel="stylesheet">
<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/css/booking.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/js/advertiserReport.js" charset="UTF-8"></script>
<div id="supplier-report">
	<h3>BOOKING(訂單)</h3>
	<script type="text/javascript">
		$(function(){

			var tfootId = 0;
			$("tfoot td").each(function(){
				$(this).prop("id","tfoot" + tfootId)
				tfootId++;
			})

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

			$("#hide-click").click(function(){
				$(".click").toggle();
				$("#yiiCGrid_c1, #yiiCGrid_c2, #yiiCGrid_c3, #yiiCGrid_c4").toggle();
				$("#tfoot1, #tfoot2, #tfoot3, #tfoot4").toggle();
			});

			$("#hide-imp").click(function(){
				$(".imp").toggle();
				$("#yiiCGrid_c5, #yiiCGrid_c6, #yiiCGrid_c7, #yiiCGrid_c8").toggle();
				$("#tfoot5, #tfoot6, #tfoot7, #tfoot8").toggle();
			});

			$("#hide-budget").click(function(){
				$(".budget").toggle();
				$("#yiiCGrid_c9, #yiiCGrid_c10, #yiiCGrid_c11, #yiiCGrid_c12").toggle();
				$("#tfoot9, #tfoot10, #tfoot11, #tfoot12").toggle();
			});			

			$("#hide-booking").click(function(){
				$(".booking-colums").toggle();
				$("#yiiCGrid_c1, #yiiCGrid_c5, #yiiCGrid_c9").toggle();
				$("#tfoot1, #tfoot5, #tfoot9").toggle();
			});	

			$("#hide-log").click(function(){
				$(".log-colums").toggle();
				$("#yiiCGrid_c2, #yiiCGrid_c6, #yiiCGrid_c10").toggle();
				$("#tfoot2, #tfoot6, #tfoot10").toggle();
			});	

			$("#hide-undo").click(function(){
				$(".undo-colums").toggle();
				$("#yiiCGrid_c3, #yiiCGrid_c7, #yiiCGrid_c11").toggle();
				$("#tfoot3, #tfoot7, #tfoot11").toggle();
			});							

			$("#hide-bookingRate").click(function(){
				$(".rate-colums").toggle();
				$("#yiiCGrid_c4, #yiiCGrid_c8, #yiiCGrid_c12").toggle();
				$("#tfoot4, #tfoot8, #tfoot12").toggle();
			});

			$("#hide-ecpc").click(function(){
				$(".ecpc").toggle();
				$("#yiiCGrid_c13").toggle();
				$("#tfoot13").toggle();
			});

			$("#hide-ecpm").click(function(){
				$(".ecpm").toggle();
				$("#yiiCGrid_c14").toggle();
				$("#tfoot14").toggle();
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
		<a href="strategyListHistory?type=2&day=<?php echo $_GET['day'];?>"  target="_new"  class="btn btn-default">看策略表</a>
	</div>
	<p>
	<div><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>實驗性功能 - 隱藏不需要的欄位 : 當您只想觀察指定的數據時候可以藉由關閉其他項目來達成</div>
	<div>
		<div class="btn-group" data-toggle="buttons">
		  <label class="btn btn-default"  id="hide-click">
		    <input type="checkbox" autocomplete="off"> 隱藏點擊
		  </label>
		  <label class="btn btn-default"  id="hide-imp">
		    <input type="checkbox" autocomplete="off"> 隱藏曝光
		  </label>
		  <label class="btn btn-default"  id="hide-budget">
		    <input type="checkbox" autocomplete="off"> 隱藏花費
		  </label>
		  <label class="btn btn-default"  id="hide-booking">
		    <input type="checkbox" autocomplete="off"> 隱藏當日預估
		  </label>
		  <label class="btn btn-default"  id="hide-log">
		    <input type="checkbox" autocomplete="off"> 隱藏實際點擊
		  </label>
		  <label class="btn btn-default"  id="hide-undo">
		    <input type="checkbox" autocomplete="off"> 隱藏未執行
		  </label>	
		  <label class="btn btn-default"  id="hide-bookingRate">
		    <input type="checkbox" autocomplete="off"> 隱藏執行率
		  </label>	
		  <label class="btn btn-default"  id="hide-ecpm">
		    <input type="checkbox" autocomplete="off"> 隱藏eCPC
		  </label>	
		  <label class="btn btn-default"  id="hide-ecpc">
		    <input type="checkbox" autocomplete="off"> 隱藏eCPM
		  </label>			  		  	  	  		  		  
		</div>
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
				'header' => "<div class='topItem'>(剩餘天數)訂單編號</div>訂單",
				'type' => "raw",
				'value'=>'"<div class=\'topItem\'>( " . $data->getSurplusDay($_GET[\'day\'],$data->campaign->end_time) . " ) " . CHtml::link($data->campaign_id,array("bookingReport/campaign","id"=>$data->campaign_id),array("target"=>"_blank")) . "</div>" . $data->campaign->campaign_name',
				// 'htmlOptions'=>array('width'=>'100','class'=>'day'),
				'filter'=>false,
				'footer'=>'總計'
			),						
			array(	
				'name' => "day_click",
				'header' => "當日點擊<br>預估",
				'type' => "raw",
				'value'=>'"<div class=\'report-txt\'>" . number_format($data->day_click, 0, "." ,",") . ( ($data->click_status == 1)? "" : "<div class=\'st" . $data->click_status . "\'></div>") . "</div>"',
				'htmlOptions'=>array('class'=>'click booking-colums'),
				'filter'=>false,
				'footer'=>number_format($day_click = $model->sumColumn($allData,"day_click"), 0, "." ,","),
			),
			array(	
				'name' => "run_click",
				'header' => "實際實行<br>點擊",
				'value'=>'number_format($data->run_click, 0, "." ,",")',
				'htmlOptions'=>array('class'=>'click log-colums'),
				'filter'=>false,
				'footer'=>number_format($run_click = $model->sumColumn($allData,"run_click"), 0, "." ,","),
			),	
			array(	
				'header' => "未執行<br>點擊",
				'value'=>'number_format(($data->run_click - $data->day_click), 0, "." ,",")',
				'htmlOptions'=>array('class'=>'click undo-colums'),
				'filter'=>false,
				'footer'=>number_format( ($run_click - $day_click) , 0, "." ,","),
			),	
			array(	
				'header' => "點擊<br>執行率",
				'value'=>'number_format((($data->day_click > 0) ? ($data->run_click / $data->day_click) * 100 : 0), 2, "." ,",") . "%"',
				'htmlOptions'=>array('class'=>'click rate-colums'),
				'filter'=>false,
				'footer'=>number_format( (($day_click > 0) ? ($run_click / $day_click) * 100 : 0) , 2, "." ,",") . "%",
			),								
			array(	
				'name' => "day_imp",
				'header' => "日曝光<br>預估",
				'type' => "raw",
				'value'=>'"<div class=\'report-txt\'>" . number_format($data->day_imp, 0, "." ,",") . ( ($data->imp_status == 1)? "" : "<div class=\'st" . $data->imp_status . "\'></div>") . "</div>"',
				'htmlOptions'=>array('class'=>'imp  booking-colums'),
				'filter'=>false,
				'footer'=>number_format($day_imp = $model->sumColumn($allData,"day_imp"), 0, "." ,","),
			),
			array(	
				'name' => "run_imp",
				'header' => "實際實行<br>曝光",
				'value'=>'number_format($data->run_imp, 0, "." ,",")',
				'htmlOptions'=>array('class'=>'imp log-colums'),
				'filter'=>false,
				'footer'=>number_format($run_imp = $model->sumColumn($allData,"run_imp"), 0, "." ,","),
			),
			array(	
				'header' => "未執行<br>曝光",
				'value'=>'number_format(($data->run_imp - $data->day_imp), 0, "." ,",")',
				'htmlOptions'=>array('class'=>'imp undo-colums'),
				'filter'=>false,
				'footer'=>number_format( ($run_imp - $day_imp) , 0, "." ,","),
			),	
			array(	
				'header' => "曝光<br>執行率",
				'value'=>'number_format((($data->day_imp > 0) ? ($data->run_imp / $data->day_imp) * 100 : 0), 2, "." ,",") . "%"',
				'htmlOptions'=>array('class'=>'imp rate-colums'),
				'filter'=>false,
				'footer'=>number_format( (($day_imp > 0) ? ($run_imp / $day_imp) * 100 : 0) , 2, "." ,",") . "%",
			),							
			array(	
				'name' => "day_budget",
				'header' => "日預算<br>預估",
				'type' => "raw",
				'value'=>'"<div class=\'report-txt\'>" . number_format($data->day_budget, 0, "." ,",") . ( ($data->budget_status == 1)? "" : "<div class=\'st" . $data->budget_status . "\'></div>") . "</div>"',
				'htmlOptions'=>array('class'=>'budget  booking-colums'),
				'filter'=>false,
				'footer'=>number_format($day_budget = $model->sumColumn($allData,"day_budget"), 0, "." ,","),
			),	
			array(	
				'name' => "run_budget",
				'header' => "實際實行<br>預算",
				'value'=>'number_format($data->run_budget, 0, "." ,",")',
				'htmlOptions'=>array('class'=>'budget undo-colums'),
				'filter'=>false,
				'footer'=>number_format($run_budget = $model->sumColumn($allData,"run_budget"), 0, "." ,","),
			),	
			array(	
				'header' => "未執行<br>預算",
				'value'=>'number_format(($data->run_budget - $data->day_budget), 0, "." ,",")',
				'htmlOptions'=>array('class'=>'budget log-colums'),
				'filter'=>false,
				'footer'=>number_format( ($run_budget  - $day_budget) , 0, "." ,","),
			),	
			array(	
				'header' => "預算<br>執行率",
				'value'=>'number_format((($data->day_budget > 0) ? ($data->run_budget / $data->day_budget) * 100 : 0), 2, "." ,",") . "%"',
				'htmlOptions'=>array('class'=>'budget rate-colums'),
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