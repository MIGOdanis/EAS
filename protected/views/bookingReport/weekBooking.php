<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/locales/bootstrap-datepicker.zh-TW.min.js" charset="UTF-8"></script>
<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/css/advertiserReport.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/js/advertiserReport.js" charset="UTF-8"></script>
<style type="text/css">
	.date-select{
		width: 280px;
		font-size: 10px;
		margin-bottom: 5px;
	}
	.date-select input{
		height: 28px;
	}	
	.date-select .input-group-addon{
		font-size: 10px;
		height: 28px;
		padding: 5px !important;
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
<div id="supplier-report">
	<h3>系統Booking預報表</h3>
	<p>
		<?php
		if(isset($_GET['type']) && $_GET['type'] > 0){
			echo ($_GET['type'] == 1)? "篩選 : 只看PC" : "篩選 : 只看MOB";
		}
		?>

	</p>
	<p>最後計算時間 : <?php echo date("Y-m-d H:i",$lastBooking);?></p>


	<div class="btn-group" role="group" aria-label="...">
		<a href="weekBooking?day=<?php echo $_GET['day']; ?>" class="btn btn-default">全部</a>
		<a href="weekBooking?type=1&day=<?php echo $_GET['day']; ?>" class="btn btn-default">只看PC</a>
		<a href="weekBooking?type=2&day=<?php echo $_GET['day']; ?>" class="btn btn-default">只看MOB</a>
		<button type="button" class="btn btn-default filter-btn" data-url="filterCampaign">
		  	<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> 訂單濾除
		</button>
		<button type="button" class="btn btn-default filter-btn" data-url="filterDate">
			<span class="glyphicon glyphicon-th"  aria-hidden="true"></span> 查詢日期
		</button>
	</div>

	<table class="table table-bordered">
	<thead>
		<th width="100">月份</th>
		<th>全月花費預計</th>
		<th>全月已執行花費</th>
		<th>全月花費達成率</th>
		<th>全月曝光預計</th>
		<th>全月已執行曝光</th>
		<th>全月曝光達成率</th>
		<th>全月點擊預計</th>
		<th>全月已執行點擊</th>
		<th>點擊達成率</th>
	</thead>
	<tbody>
		<tr>
			<td><?php echo date("m",strtotime(date("Y-m-d", $day) . "-1 month")); ?>月</td>
			<td><?php echo number_format($pastMonth->day_budget, 0, "." ,","); ?></td>
			<td><?php echo number_format($pastMonth->run_budget, 0, "." ,","); ?></td>
			<td><?php echo number_format((($pastMonth->day_budget > 0)? (($pastMonth->run_budget / $pastMonth->day_budget) * 100) : 0), 2, "." ,",") ?>%</td>
			<td><?php echo number_format($pastMonth->day_imp, 0, "." ,","); ?></td>
			<td><?php echo number_format($pastMonth->run_imp, 0, "." ,","); ?></td>
			<td><?php echo number_format((($pastMonth->day_imp > 0)? (($pastMonth->run_imp / $pastMonth->day_imp) * 100) : 0), 2, "." ,",") ?>%</td>
			<td><?php echo number_format($pastMonth->day_click, 0, "." ,","); ?></td>
			<td><?php echo number_format($pastMonth->run_click, 0, "." ,","); ?></td>
			<td><?php echo number_format((($pastMonth->day_click > 0)? (($pastMonth->run_click / $pastMonth->day_click) * 100) : 0), 2, "." ,",") ?>%</td>
		</tr>
		<tr>
			<td><?php echo date("m", $day); ?>月</td>
			<td><?php echo number_format($thisMonth->day_budget, 0, "." ,","); ?></td>
			<td><?php echo number_format($thisMonth->run_budget, 0, "." ,","); ?></td>
			<td><?php echo number_format((($thisMonth->day_budget > 0)? (($thisMonth->run_budget / $thisMonth->day_budget) * 100) : 0), 2, "." ,",") ?>%</td>
			<td><?php echo number_format($thisMonth->day_imp, 0, "." ,","); ?></td>
			<td><?php echo number_format($thisMonth->run_imp, 0, "." ,","); ?></td>
			<td><?php echo number_format((($thisMonth->day_imp > 0)? (($thisMonth->run_imp / $thisMonth->day_imp) * 100) : 0), 2, "." ,",") ?>%</td>
			<td><?php echo number_format($thisMonth->day_click, 0, "." ,","); ?></td>
			<td><?php echo number_format($thisMonth->run_click, 0, "." ,","); ?></td>
			<td><?php echo number_format((($thisMonth->day_click > 0)? (($thisMonth->run_click / $thisMonth->day_click) * 100) : 0), 2, "." ,",") ?>%</td>
		</tr>
		<tr>
			<td><?php echo date("m",strtotime(date("Y-m-d", $day) . "+1 month")); ?>月</td>
			<td><?php echo number_format($nextMonth->day_budget, 0, "." ,","); ?></td>
			<td><?php echo number_format($nextMonth->run_budget, 0, "." ,","); ?></td>
			<td><?php echo number_format((($nextMonth->day_budget > 0)? (($nextMonth->run_budget / $nextMonth->day_budget) * 100) : 0), 2, "." ,",") ?>%</td>
			<td><?php echo number_format($nextMonth->day_imp, 0, "." ,","); ?></td>
			<td><?php echo number_format($nextMonth->run_imp, 0, "." ,","); ?></td>
			<td><?php echo number_format((($nextMonth->day_imp > 0)? (($nextMonth->run_imp / $nextMonth->day_imp) * 100) : 0), 2, "." ,",") ?>%</td>
			<td><?php echo number_format($nextMonth->day_click, 0, "." ,","); ?></td>
			<td><?php echo number_format($nextMonth->run_click, 0, "." ,","); ?></td>
			<td><?php echo number_format((($nextMonth->day_click > 0)? (($nextMonth->run_click / $nextMonth->day_click) * 100) : 0), 2, "." ,",") ?>%</td>
		</tr>		
		
	</tbody>
	</table>

	<table class="table table-bordered">
	<thead>
		<th width="100">日期</th>
		<th>日花費估計</th>
		<th>已執行花費</th>
		<th>花費達成率</th>
		<th>日曝光預計</th>
		<th>已執行曝光</th>
		<th>曝光達成率</th>
		<th>日點擊預計</th>
		<th>已執行點擊</th>
		<th>點擊達成率</th>
	</thead>
	<tbody>

	<?php foreach ($past as $value) { ?>
		<tr>
		<td><?php echo CHtml::link(date("Y-m-d",$value->booking_time),array("bookingReport/strategyListHistory","day"=>date("Y-m-d",$value->booking_time)),array("target"=>"_blank")); ?></td>
		<td><?php echo number_format($value->day_budget, 0, "." ,","); ?></td>
		<td><?php echo number_format($value->run_budget, 0, "." ,","); ?></td>
		<td><?php echo number_format((($value->day_budget > 0)? (($value->run_budget / $value->day_budget) * 100) : 0), 2, "." ,",") ?>%</td>
		<td><?php echo number_format($value->day_imp, 0, "." ,","); ?></td>
		<td><?php echo number_format($value->run_imp, 0, "." ,","); ?></td>
		<td><?php echo number_format((($value->day_imp > 0)? (($value->run_imp / $value->day_imp) * 100) : 0), 2, "." ,",") ?>%</td>
		<td><?php echo number_format($value->day_click, 0, "." ,","); ?></td>
		<td><?php echo number_format($value->run_click, 0, "." ,","); ?></td>
		<td><?php echo number_format((($value->day_click > 0)? (($value->run_click / $value->day_click) * 100) : 0), 2, "." ,",") ?>%</td>
		</tr>
	<?php } ?>

	<?php foreach ($future as $value) { ?>
		<tr>
		<td><?php echo CHtml::link(date("Y-m-d",$value->booking_time),array("bookingReport/strategyListHistory","day"=>date("Y-m-d",$value->booking_time)),array("target"=>"_blank")); ?></td>
		<td><?php echo number_format($value->day_budget, 0, "." ,","); ?></td>
		<td>-</td>
		<td>-</td>
		<td><?php echo number_format($value->day_imp, 0, "." ,","); ?></td>
		<td>-</td>
		<td>-</td>
		<td><?php echo number_format($value->day_click, 0, "." ,","); ?></td>
		<td>-</td>
		<td>-</td>
		</tr>
	<?php } ?>

	</tbody>
	</table>
</div>