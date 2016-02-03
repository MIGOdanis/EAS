<script>
$(function(){
	var site;
	$(".report-site-list").click(function(){
		$(".report-site-list").removeClass("report-site-active");
		$(".report-adspace-list").removeClass("report-adspace-active");
		$(".siteList").removeClass("report-site-active");
		$(this).addClass("report-site-active");		
		var sid = $(this).data("site");
		site = sid;
		adSpace = 0;
		getAdSpace();
	});	

	$(".siteList").click(function(){
		$(this).addClass("report-site-active");	
		getMySite();
	});	

	function getMySite(){
		$(".report-site-list").removeClass("report-site-active");
		$.ajax({
				url:"getMySite",
				data: { 
					site : site,
				},
				success:function(html){
					$('#display-supplier-mySite').html(html);
					$('#loading-supplier-mySite').hide();
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

	function getAdSpace(){
		site = $(".report-site-active").data("site");
		$('#loading-supplier-mySite').show();
		$('#display-supplier-mySite').html("");
		$.ajax({
				url:"getMyAdSpace",
				data: { 
					site : site,
				},
				success:function(html){
					$('#display-supplier-mySite').html(html);
					$('#loading-supplier-mySite').hide();
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

	$(".siteList").addClass("report-site-active");	
	getMySite();
	
})
</script>
<style type="text/css">
	#supplier-mySite{
		padding-top: 10px;
	}
	#loading-supplier-mySite{
		display: none;
	}
	.display-supplier-adSpace{
		border-bottom: 1px solid #eee;
		padding-bottom: 5px;
	}
</style>
<div id="supplier-mySite">
	<div id="loading-supplier-mySite">載入中..</div>
	<div id="display-supplier-mySite">

	</div>
</div>
