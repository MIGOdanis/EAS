<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/locales/bootstrap-datepicker.zh-TW.min.js" charset="UTF-8"></script>
<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/css/advertiserReport.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/js/advertiserReport.js" charset="UTF-8"></script>
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
		<a href="weekBooking" class="btn btn-default">全部</a>
		<a href="weekBooking?type=1" class="btn btn-default">只看PC</a>
		<a href="weekBooking?type=2" class="btn btn-default">只看MOB</a>
		<button type="button" class="btn btn-default" id="filter-btn">
		  	<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> 訂單濾除
		</button>
	</div>



	<table class="table table-bordered">
	<thead>
		<th>日期</th>
		<th>日預算估計</th>
		<th>已執行預算</th>
		<th>預算達成率</th>
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
		<td><?php echo CHtml::link(date("Y-m-d",$value->booking_time),array("bookingReport/campaignListHistory","day"=>date("Y-m-d",$value->booking_time)),array("target"=>"_blank")); ?></td>
		<td><?php echo number_format($value->day_budget, 0, "." ,","); ?></td>
		<td><?php echo number_format($value->run_budget, 0, "." ,","); ?></td>
		<td><?php echo number_format((($value->day_budget > 0)? (($value->run_budget / $value->day_budget) * 100) : 0), 2, "." ,",") ?>%</td>
		<td><?php echo number_format($value->day_imp, 0, "." ,","); ?></td>
		<td><?php echo number_format($value->run_imp, 0, "." ,","); ?></td>
		<td><?php echo number_format((($value->day_imp > 0)? (($value->run_imp / $value->day_imp) * 100) : 0), 2, "." ,",") ?>%</td>
		<td><?php echo number_format($value->day_click, 0, "." ,","); ?></td>
		<td><?php echo number_format($value->run_click, 0, "." ,","); ?></td>
		<td><?php echo number_format((($value->day_click > 0)? (($value->run_click / $value->day_click) * 100) : 0), 2, "." ,",") ?>%</td>
		<tr>
	<?php } ?>

	<?php foreach ($future as $value) { ?>
		<tr>
		<td><?php echo CHtml::link(date("Y-m-d",$value->booking_time),array("bookingReport/campaignListHistory","day"=>date("Y-m-d",$value->booking_time)),array("target"=>"_blank")); ?></td>
		<td><?php echo number_format($value->day_budget, 0, "." ,","); ?></td>
		<td>-</td>
		<td>-</td>
		<td><?php echo number_format($value->day_imp, 0, "." ,","); ?></td>
		<td>-</td>
		<td>-</td>
		<td><?php echo number_format($value->day_click, 0, "." ,","); ?></td>
		<td>-</td>
		<td>-</td>
		<tr>
	<?php } ?>

	</tbody>
	</table>
</div>