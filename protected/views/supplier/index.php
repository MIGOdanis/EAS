<script type="text/javascript">
	$(function(){
		$('#loading-index-report').show();
		var type = "all";
		$.ajax({
				url:"getIndexReport",
				data: { type : type },
				success:function(html){
					$('#display-index-report').html(html);
					$('#loading-index-report').hide();
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
	})
</script>
<style type="text/css">
	#loading-index-report{
		display: none;
	}
</style>
<div id="index-report-box">
	<strong><h3>收益總覽</h3></strong>
	<div id="index-report">
		<div id="loading-index-report">載入中..</div>
		<div id="display-index-report"></div>
	</div>
</div>

