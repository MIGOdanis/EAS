<script type="text/javascript">
	$(function(){
		var type = "7day";
		function getReport(){
			$('#loading-index-report').show();
			$('#display-index-report').html("");
			$.ajax({
					url:"getIndexReport",
					data: { type : type },
					success:function(html){
						$('#display-index-report').html(html);
						$('#loading-index-report').hide();
						initLinkBtn();
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
		}	

		function initLinkBtn(){
			$(".sort-link").click(function(){
				var url = $(this).prop("href");
				$('#loading-index-report').show();
				$('#display-index-report').html("");
				$.ajax({
					url:url,
					data: { type : type },
					success:function(html){
						$('#display-index-report').html(html);
						$('#loading-index-report').hide();
						initLinkBtn();
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
				return false;		
			});
		};

		$(".select-report").click(function(){
			type = $(this).data("type");
			$("#index-report-dropup-now").html($(this).text());
			getReport();
			// return false;
		});
		
		getReport();
	})
</script>
<style type="text/css">
	#loading-index-report{
		display: none;
	}
	.dropup{
		float: left;
	}
	.index-report-title{
		float: left;
		height: 34px;
		line-height: 34px;
		font-weight: bold;
		font-size: 20px;
		margin-right: 10px;
	}	
</style>
<div id="index-report-box">
	<div class="index-report-title">收益總覽</div>
			
<div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <span id="index-report-dropup-now">最近7天</span>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <li><a href="#" data-type="7day" class="select-report">最近7天</a></li>
    <li><a href="#" data-type="30day" class="select-report">最近30天</a></li>
    <li><a href="#" data-type="pastMonth" class="select-report">上個月</a></li>
    <li><a href="#" data-type="thisMonth" class="select-report">本月</a></li>
  </ul>
</div>
		


	<div id="index-report">
		<div id="loading-index-report">載入中..</div>
		<div id="display-index-report"></div>
	</div>
</div>

