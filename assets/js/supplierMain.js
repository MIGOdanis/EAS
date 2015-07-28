var messageGroupStatus = false;

$(function(){
	
	$('html').click(function() {
		$("#message-group").hide();
		messageGroupStatus = false;
		$("#message-box").removeClass("open");
	});

	initViewMsg();
})

function updateMsg(){
	messageGroupStatus = false;
	$.ajax({
		url: "updateMessage",
		success:function(html){
			$('#message-box').html(html);
			initViewMsg();
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

function initViewMsg(){
	$("#message-btn").click(function(event){
		if(messageGroupStatus){
			$("#message-group").hide();
			messageGroupStatus = false;
			$(this).parent().removeClass("open");
		}else{
			$("#message-group").show();
			messageGroupStatus = true;
			$(this).parent().addClass("open");
		}
		event.stopPropagation();
		return false;//阻止a标签
	});

	$(".viewMessage").click(function(){
		var href = $(this).prop("href");
		$.ajax({
			url: href,
			success:function(html){
				updateMsg();
				$('#modal-content').html(html);
				$('#modal').modal('show');

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
		return false;//阻止a标签
	});

	$(".setAllReadBtn").click(function(event){
		var href = $(this).prop("href");
		$(this).html("設置中..");
		$.ajax({
			url: href,
			success:function(){
				updateMsg();
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
		event.stopPropagation();
		return false;//阻止a标签		
	})
}