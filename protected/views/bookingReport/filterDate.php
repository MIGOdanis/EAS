<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel">查詢基準日期</h4>
</div>
<script type="text/javascript">
	$(function(){
		$('.input-group.date').datepicker({
		    format: "yyyy-mm-dd",
		    calendarWeeks: true,
		    autoclose: true,
		    todayHighlight: true,
		    language: "zh-TW",
		});
	})
</script>
<form action="" method="get">
	<div class="modal-body">
		<div class="input-group date">
		  <input type="text" name="day" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
		</div>
	</div>
	<div class="modal-footer">
		<a href="?type=<?php echo $_GET['type'];?>" class="btn btn-default close-btn">今天</a>
		<input type="submit" class="btn btn-default" value="更新" />
	</div>
</form>