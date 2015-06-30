$(function(){
	// 檢舉回報
	$('.status').live('change', function(e){
		var type = $(this).val();
		var id = $(this).data("id");
		//var controller = $(this).data("type");
		var select = this;
		
		var data = {id:id,type:type};
		var url = "status";

		$.post(url , data, function( data ) {
		    if (data.data.error === 0) {
	    		$("#ub"+id).text(data.data.update_name);
	    		$("#ut"+id).text(data.data.update_time);
		    }else{
		    	if(data.data.update_by > 0){
		    		alert("此件已在處理中");
		    		$("#ub"+id).text(data.data.update_name);
		    		$("#ut"+id).text(data.data.update_time);
		    		if(data.data.status != 0){
	    				$(select).attr( "disabled", "disabled" );
	    			}
		    		$(select)[0].selectedIndex = data.data.status;
		    	}else{
		      		alert("選取失敗，請按F5重新整理後再試");
		    	}
		    }               
		},'json')
		.fail(function(e) {
		    if(e.status == 403){
		        alert("權限不足");
		    }
		});
	});		
});