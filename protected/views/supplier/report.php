<link href="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/bootstrap-datepicker/locales/bootstrap-datepicker.zh-TW.min.js" charset="UTF-8"></script>
<style type="text/css">
	#filter{
		width: 100%;
		height: 50px;
		line-height: 50px;
		border-bottom: solid 1px #e7e7e7;
		background-color: #f8f8f8;
		
	}
	#right-main{
		padding-left: 0px;
	}
	.filter-box{
		float: right;
		margin-right: 15px;
	}
	.filter-datepicker{
		width: 300px;
		padding-top: 10px;
		margin-right: 0px;
	}

	.filter-datepicker .col-md-5{
		width: 100%;
	}

	.btn-default{
		font-size: 10px;
		height: 29px;
		margin-bottom: 4px;
	}

	#supplier-report{
		padding:15px;
	}
</style>

<div id="report">
	<div id="filter">
		<div class="filter-box">
			<div class="dropdown">
			  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			    <span id="supplier-report-dropup-now">最近7天</span>
			    <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
			    <li><a href="#" data-type="7day" class="select-report">最近7天</a></li>
			    <li><a href="#" data-type="30day" class="select-report">最近30天</a></li>
			    <li><a href="#" data-type="pastMonth" class="select-report">上個月</a></li>
			    <li><a href="#" data-type="thisMonth" class="select-report">本月</a></li>
			  </ul>
			</div>
		</div>

		<div class="filter-box">
			<button class="btn btn-default" id="run-day" type="submit">套用</button>
		</div>		

		<div class="filter-box filter-datepicker">
			<div class="span5 col-md-5" id="sandbox-container">
				<div class="input-daterange input-group" id="datepicker">
			    <input type="text" class="input-sm form-control" id="startDay">
			    <span class="input-group-addon">至</span>
			    <input type="text" class="input-sm form-control" id="endDay">
				</div>
			</div>
		</div>
	</div>
</div>
<div id="supplier-report">
	<div id="loading-supplier-report">載入中..</div>
	<div id="display-supplier-report"></div>
</div>
<script type="text/javascript">
$(function(){
	var adSpace = 0;
	var site = 0;
	var startDay;
	var endDay;
	var type = "7day";

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

	$("#run-day").click(function(){
		getReport();
	});

	$('#sandbox-container .input-daterange').datepicker({
	    language: "zh-TW",
	    format: "yyyy/mm/dd"
	}).on("show", function(e){
        $("#supplier-report-dropup-now").html("自訂");
		startDay = $("#startDay").val();
		endDay = $("#endDay").val();;
		type = "custom";        
    });

	function getReport(){
		$('#loading-supplier-report').show();
		$('#display-supplier-report').html("");
		$.ajax({
				url:"getSupplierReport",
				data: { 
					type : type,
					site : site,
					adSpace : adSpace,
					startDay : startDay,
					endDay : endDay,
				},
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
	}	

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
	
	getReport();    
})
</script>