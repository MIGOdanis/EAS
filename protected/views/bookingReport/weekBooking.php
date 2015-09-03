<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/locales/bootstrap-datepicker.zh-TW.min.js" charset="UTF-8"></script>
<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/css/advertiserReport.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/js/advertiserReport.js" charset="UTF-8"></script>
<div id="supplier-report">
	<h3>一周Booking預報</h3>
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
	<td><?php echo $value['date'] . "(-" . $value['day'] . "D)"; ?></td>
	<td><?php echo number_format($value['day_budget'], 0, "." ,","); ?></td>
	<td><?php echo number_format($value['run_budget'], 0, "." ,","); ?></td>
	<td><?php echo number_format((($value['day_budget'] > 0)? (($value['run_budget'] / $value['day_budget']) * 100) : 0), 2, "." ,",") ?>%</td>
	<td><?php echo number_format($value['day_imp'], 0, "." ,","); ?></td>
	<td><?php echo number_format($value['run_imp'], 0, "." ,","); ?></td>
	<td><?php echo number_format((($value['day_imp'] > 0)? (($value['run_imp'] / $value['day_imp']) * 100) : 0), 2, "." ,",") ?>%</td>
	<td><?php echo number_format($value['day_click'], 0, "." ,","); ?></td>
	<td><?php echo number_format($value['run_click'], 0, "." ,","); ?></td>
	<td><?php echo number_format((($value['day_click'] > 0)? (($value['run_click'] / $value['day_click']) * 100) : 0), 2, "." ,",") ?>%</td>
	<tr>
<?php } ?>

<?php foreach ($future as $value) { ?>
	<tr>
	<td><?php echo $value['date'] . "(+" . $value['day'] . "D)"; ?></td>
	<td><?php echo number_format($value['day_budget'], 0, "." ,","); ?></td>
	<td>-</td>
	<td>-</td>
	<td><?php echo number_format($value['day_imp'], 0, "." ,","); ?></td>
	<td>-</td>
	<td>-</td>
	<td><?php echo number_format($value['day_click'], 0, "." ,","); ?></td>
	<td>-</td>
	<td>-</td>
	<tr>
<?php } ?>

</tbody>
</table>
</div>