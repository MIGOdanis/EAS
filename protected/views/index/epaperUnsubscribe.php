<div class="page-header">
	<h1>取消訂閱</h1>
</div>
<div style="text-align: center;">
	<h3>
	<?php 
	if($model===null){
		echo "沒有找到您的訂閱資訊";
	}else{
		echo "已經取消您的訂閱";
	}
	?>	
	</h3>
	<h3>您可以連絡 : <?php echo Yii::app()->params['epReturnPath'];?> 取得更多訊息</h3>
</div>