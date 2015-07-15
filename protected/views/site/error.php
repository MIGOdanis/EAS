<style type="text/css">
	.error{
		padding: 10px;
		margin-bottom: 20px;
		font-size: 15px;
	}
</style>
<div class="page-header">
  <h1>Error - <?php echo $code; ?></h1>
</div>
<div class="error">
	<?php
		if ($code == "404") {
			echo "找不到這個頁面";
		}elseif($code == "403"){
			echo "拒絕訪問";
		}elseif($code == "500"){
			echo "網站可能維護中";
		}elseif($code == "400"){
			echo "網站可能維護中";
		}else{
			echo "網站可能維護中";
		}

	?>
	<h5>請返回上一頁</h5>
</div>
