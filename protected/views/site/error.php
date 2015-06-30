<style type="text/css">
	.error{
		padding: 10px;
		margin-bottom: 20px;
		font-size: 15px;
	}
</style>
<div class="page-header">
  <h1>錯誤 <?php echo $code; ?></h1>
</div>
<div class="error">
	<?php
		if ($code == "404") {
			echo "地球上沒有這個頁面!!! (可能在火星上)";
		}elseif($code == "403"){
			echo "權限不足已進入這個頁面.. (問問村長?)";
		}elseif($code == "500"){
			echo "這個頁面派起阿 (快截圖回報技術部!)";
		}elseif($code == "400"){
			echo "這個網頁不是這樣使用的阿...";
		}

	?>
</div>