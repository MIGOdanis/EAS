<div class="page-header">
	<h1>
		開關帳管理 <?php echo ($accountsStatus->value == 1) ? "<span class='label label-success'>開帳中</span>" : "<span class='label label-danger'>關帳中</span>"; ?>
	</h1>
	<H3>
		最後更新時間 : <?php echo date("Y-m-d H:i", $accountUpdateTime->value);?>
	</H3>
</div>
<p>
	<a href="admin?status=1" class="btn btn-danger btn-lg oc-btn"><?php echo ($accountsStatus->value == 1) ? "執行關帳" : "執行開帳"; ?></a>
</p>
<script>
$(function(){
	$(".oc-btn").click(function(){
		if(confirm("請確認是否<?php echo ($accountsStatus->value == 1) ? '執行關帳' : '執行開帳'; ?>")){
			if(confirm("請再次確認是否<?php echo ($accountsStatus->value == 1) ? '執行關帳' : '執行開帳'; ?>，確認請按下[取消]")){
				return false;
			}else{
				
			}
		}else{
			return false;
		}
	});
})
</script>