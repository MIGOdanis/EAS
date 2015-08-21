var startDay;
var endDay;
var supplierId;
var siteId;
var adSpaceId;
var showNoPay;
var filterStatus = "close";

if(typeof(type == "undefined")){
	var type = "yesterday";
}

function moveFilter(){
	if (filterStatus == "close") {
		filterStatus = "runing";
		$( "#filter" ).animate({
			width: "350",
		}, 800, function() {
			filterStatus = "open";
			$( "#filter" ).css("overflow","");
			$( "#filter-list" ).toggle("normal");
			$( "#filter-list" ).css("overflow","");
		});
	}else if(filterStatus == "open"){
		$( "#filter-list" ).toggle("normal");
		filterStatus = "runing";
		$( "#filter" ).animate({
			width: "0",
		}, 800, function() {
			filterStatus = "close";
			$( "#filter" ).css("overflow","");
			$( "#filter-list" ).css("overflow","");
		});		
	}
}


function getReport(){
	moveFilter();
	$('#loading-supplier-report').show();
	$('#display-supplier-report').html("");
	supplierId = $("#supplier-id").val();
	siteId = $("#site-id").val();
	adSpaceId = $("#adSpace-id").val();
	startDay = $("#startDay").val();
	endDay = $("#endDay").val();
	if($("#showNoPay").prop("checked") == true){
		showNoPay = 1;
	}else{
		showNoPay = 0;
	}
	
	$.ajax({
			url:reportUrl,
			data: { 
				ajax : "1",
				type : type,
				startDay : startDay,
				endDay : endDay,
				supplierId : supplierId,
				siteId : siteId,
				adSpaceId : adSpaceId,
				showNoPay : showNoPay
			},
			success:function(html){
				$("#export").prop("href", this.url + "&export=1");

				$('#display-supplier-report').html(html);
				$('#loading-supplier-report').hide();
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
		$('#loading-supplier-report').show();
		$('#display-supplier-report').html("");
		$.ajax({
				url:url,
				success:function(html){
					$('#display-supplier-report').html(html);
					$('#loading-supplier-report').hide();
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

$(function(){
	$("#run-day").click(function(){
		supplierId = $("#supplier-id").val();
		siteId = $("#site-id").val();
		adSpaceId = $("#adSpace-id").val();
		getReport();
		$(".name a").each(function(){
			$(this).prop("href",$(this).prop("href")+"&type="+type+"&startDay="+startDay+"&endDay="+endDay)
		});
	});

	$("#filter-open-btn").click(function(){
		moveFilter();
	});

	$('#sandbox-container .input-daterange').datepicker({
	    language: "zh-TW",
	    format: "yyyy-mm-dd"
	}).on("show", function(e){
        $("#supplier-report-dropup-now").html("自訂");
		type = "custom";        
    });

	

	$(".select-report").click(function(){
		type = $(this).data("type");
		$("#supplier-report-dropup-now").html($(this).text());
		getReport();
		// return false;
	});

	$(".report-site-list").click(function(){
		$(".report-site-list").removeClass("report-site-active");
		$(".report-adspace-list").removeClass("report-adspace-active");
		$(this).addClass("report-site-active");		
		var sid = $(this).data("site");
		$(".report-adspace-list").hide();
		$(".site-" + sid).show();
		site = sid;
		adSpace = 0;
		getReport();
	});	

	$(".report-adspace-list").click(function(){
		$(".report-adspace-list").removeClass("report-adspace-active");
		$(this).addClass("report-adspace-active");
		site = $(this).data("site");
		adSpace = $(this).data("adspace");
		getReport();
	});	

	moveFilter();
    
})