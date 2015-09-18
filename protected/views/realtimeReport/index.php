<style type="text/css">
	#content-singe{
		width: 100%;
		overflow-x:auto; 
		padding-top: 15px;
		padding-bottom: 15px;
	}

	#log{
		width: 100%;
		height: 700px;
		overflow: scroll;
	}			
</style>
<script type="text/javascript">
	$(function(){
		getdata();
		setInterval(function(){ 
			getdata();
		}, 60000);
	})

	var click = 0;
	var imp = 0;
	var budget = 0;
	function getdata(){
		var url = "index?ajax=1";
		$.ajax({
			url:url,
			data: {pid:$(this).data("page")},
			dataType : "json",
			success:function(data){
				console.log(data.daily_hit_budget);
				var html = $("#log").html();
				var log = data.time + " : CLICK : " + (data.daily_hit_click - click) + " | IMP : " + (data.daily_hit_pv - imp) + " | BUGET : " + (data.daily_hit_budget - budget) + "<br>"
				$("#log").html(log+html);
				click = data.daily_hit_click;
				imp = data.daily_hit_pv;
				budget = data.daily_hit_budget;
			}
		})
        .fail(function(e) {
            if(e.status == 403){
                window.location.reload();
            }
        });

	}
</script>
<div id="grp">

</div>
<div id="log">
	
</div>