var startDay;
var endDay;
var CampaignId;
var StrategyId;
var siteId;
var adSpaceId;
var filterStatus = "close";
var tabs = 1;
var activeTab = "def";
var typeCHT = "昨天";
var system;
var exportUrl = new Array;

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
			$( "#filter-list" ).fadeToggle("normal");
			$( "#filter-list" ).css("overflow","");
		});
	}else if(filterStatus == "open"){
		$( "#filter-list" ).fadeToggle("normal");
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
	var thisTab = activeTab;
	changeName("載入中....",thisTab);
	moveFilter()
	$('#' + thisTab + '-body .display-supplier-report').html("");
	CampaignId = $("#Campaign_id").val();
	StrategyId = $("#Strategy_id").val();
	siteId = $("#site-id").val();
	adSpaceId = $("#adSpace-id").val();
	startDay = $("#startDay").val();
	endDay = $("#endDay").val();
	$.ajax({
			url:reportUrl,
			data: { 
				ajax : "1",
				type : type,
				startDay : startDay,
				endDay : endDay,
				system : system,
				CampaignId : CampaignId,
				StrategyId : StrategyId,
				siteId : siteId,
				adSpaceId : adSpaceId
			},
			success:function(html){
				$("#export").prop("href", this.url + "&export=1");

				$('#' + thisTab + '-body .display-supplier-report').html(html);
	

				var tabname = ""


				if(CampaignId != "" && CampaignId != undefined){
					tabname+= "CAM:" + CampaignId + " | "
				}

				if(StrategyId != "" && StrategyId != undefined){
					tabname+= "STY:" + StrategyId + " | "
				}


				if(siteId != "" && siteId != undefined){
					tabname+= "ST:" + siteId + " | "
				}

				if(adSpaceId != "" && adSpaceId != undefined){
					tabname+= "AS:" + adSpaceId + " | "
				}


				tabname += "D:" + typeCHT;

				changeName(tabname,thisTab);
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

function changeName(str,tab){
	$("#" + tab + " a").html(str);
}

$(function(){
	$("#run-day").click(function(){
		CampaignId = $("#Campaign_id").val();
		siteId = $("#site-id").val();
		adSpaceId = $("#adSpace-id").val();
		getReport();
		$(".name a").each(function(){
			$(this).prop("href",$(this).prop("href")+"&type="+type+"&startDay="+startDay+"&endDay="+endDay)
		});
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
		typeCHT = $(this).html();
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

	$(".system-btn-group .btn").click(function(){
		system = $(this).data("status");
	});


	$("#createNewTab").click(function(){
		createNewTab();
	})

	moveFilter();

	$(document).keydown(function(e) {
		if(keyEvent && e.keyCode == 70){
			keyEvent = false;
			moveFilter();
		}

		if(keyEvent && e.keyCode == 68){
			keyEvent = false;
			location.href = $("#export").prop("href");
		}

		if(keyEvent && e.keyCode == 78){
			keyEvent = false;
			createNewTab();
		}

	});	
    
})

function getround(min,max) {
	return Math.round(Math.random()*(max-min)+min);
}

function seteventTabs(){
	$(".tabs-btn").click(function(){
		var id = $(this).prop("id");
		// console.log(id);
		$(".tab-body").hide();
		$("#" + id + "-body").show();
		activeTab = id;

		$("#export").prop("href", exportUrl[id])
	})	
}

function createNewTab(){
	var newId = "nt" + getround(0,10)+getround(0,10)+getround(0,10)+getround(0,10);
	var newTab = document.createElement("li");  // Create with DOM
	newTab.setAttribute("role","presentation");
	newTab.setAttribute("id",newId);
	newTab.setAttribute("class","tabs-btn");
	newTab.innerHTML = '<a href="#home" aria-controls="home" role="tab" data-toggle="tab">新分頁</a>';
	$("#createNewTab").before(newTab);
	$("#" + newId).tab('show');
	
	var newTabBody = document.createElement("div");
	newTabBody.setAttribute("id",newId + "-body");
	newTabBody.setAttribute("class","tab-body");
	newTabBody.innerHTML='<div class="loading-supplier-report">載入中..</div><div class="display-supplier-report">請操作條件!</div>'

	 $("#report-group").append(newTabBody);
	 $(".tab-body").hide();
	 activeTab = newId;
	 tabs++;

	 if(tabs == 4){
	 	$("#createNewTab").fadeToggle(200);
	 }

	 seteventTabs();
	 if(filterStatus == "close"){
	 	moveFilter();
	 }
	 
	 $("#" + newId + "-body").show();
	 console.log("#" + newId + "-body");
}