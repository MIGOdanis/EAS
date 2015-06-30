$(function() {
	updateTC();
	initSortableOfForm();
	initrmoj();
	
	//新增物件
	$(".select-btn").live("click",function() {
		var url = "selectType";
		$.ajax({
			//type: 'POST',
			url:url,
			data: {pid:$(this).data("page")},
			async: false,
			success:function(html){
				$('#modal-content').html(html);
				$('#modal').modal('show');
			}
		})
        .fail(function(e) {
            if(e.status == 403){
                window.location.reload();
            }
        });
		return false;//阻止a标签		
	});

	$(".setting-btn").live("click",function() {
		var url = "updateType?pageCount="+$('#pageCount').val();
		var objid = $(this).data("objid");
		$.ajax({
			type: 'POST',
			url:url,
			data: {
				type: $(this).data("type") ,
				objid : objid,
				data : $("#hide-"+objid).val()
			},

			success:function(html){
				$('#modal-content').html(html);
				$('#modal').modal('show');
			}
		})
        .fail(function(e) {
            if(e.status == 403){
                window.location.reload();
            }
        });
		return false;//阻止a标签		
	});

	//實時更新title
	$("#Forms_title").keyup(function() {
		updateTC(this);
	});

	//實時更新caption
	$("#Forms_caption").keyup(function() {
		updateTC(this);
	});		

})

function updateTC(){
	var url = document.location.href;
	if(url.indexOf('update')!=-1){
		var p1 = jQuery.parseJSON($("#hide-1").val());
		p1.title = $("#Forms_title").val();
		p1.caption = $("#Forms_caption").val().replace(/\n/g,"<br>");
		$("#hide-1").val(JSON.stringify(p1));
		$("#page1-group .title-group h1").html($("#Forms_title").val());
		$("#page1-group .title-group h3").html($("#Forms_caption").val().replace(/\n/g,"<br>"));
	}else{
		$("#headerTitle").html($("#Forms_title").val());
		$("#headerCaption").html($("#Forms_caption").val().replace(/\n/g,"<br>"));
	}	

}

function initSortableOfForm(){
	$( ".ojb-group" ).sortable({
		connectWith: ".ojb-group",
		dropOnEmpty: true
	});			
}

function initrmoj(){
	$(".rm-oj").click(function() {
		var oj = $(this).data("objid");
		if(!confirm("確定是否刪除?")){
			return false;
		}else{		
			if(active == 2){
					console.log(oj);
					$("#"+oj).remove();

						$('#pageCount').val(parseInt($('#pageCount').val())-1);
						updatePOS();
						checkPOS();
						$(".tot-page").html($('#pageCount').val());
						var r = 1;
						$(".this-page").each(function(){
							r++;
					       	$(this).html(r);
					    });
					
			}else{
				$("#"+oj).addClass("rm-after-start");
				if($(this).data("type") == "newPage"){
					console.log($(this).data("page"));
					var json = JSON.parse($("#hide-" + $(this).data("page")).val());
					json.display = 0 ;
					$("#hide-" + $(this).data("page")).val(JSON.stringify(json));
				}else{
					var json = JSON.parse($("#hide-" + oj).val());
					json.display = 0 ;
					$("#hide-" + oj).val(JSON.stringify(json));
				}
			}
		}
	});
}

function updatePOS(){
	var p = parseInt($('#pageCount').val());
	var items = '<li class="page1-over-selected" ><a class="page-over-selected" data-value="next">下一頁</a></li>';			
	for (i=1; i <= p; i++) {
		items += '<li class="page1-over-selected" ><a class="page-over-selected" data-value="'+i+'">第'+i+'頁</a></li>';
	}
	items += '<li class="page1-over-selected"><a class="page-over-selected"  data-value="end">提交表單</a></li>';

	$(".page-over-dropdown-menu").html(items);
}

$(".page-over-selected").live("click",function() {
	var page = $(this).parent().parent().data("page");
	console.log(page);
	var json = JSON.parse($("#page"+page+"-over").val());
	json.value = $(this).data("value");
	$("#page"+page+"-over").val(JSON.stringify(json));
	$("#page"+page+"-over-select").html($(this).html());
	$('[data-toggle="dropdown"]').parent().removeClass('open');
	return false;//阻止a标签		
});

function checkPOS(){
	$(".page-over input").each(function(){
	  	var json = JSON.parse($(this).val());
	  	if(json.value !="end" && json.value !="next" && json.value > p){
	  		json.value = "end";
	  		$(this).val(JSON.stringify(json));
	  		$("#page"+json.page+"-over-select").html("提交表單");
	  	}
	});		
}