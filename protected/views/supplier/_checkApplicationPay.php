<?php

$count_monies = $model->count_monies + $countYearAccounts;
function tax($type,$count_monies){
	$tax = Yii::app()->params['taxType'][$type];
	if($type == 1 && $count_monies < 20000)
		$tax = 1;

	return round($count_monies * $tax);
	
}
?>
<style type="text/css">
	.wait-btn{
		display: none;
	}
</style>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel">確認請款</h4>
	<small>確認您將申請如下款項</small>
</div>
<div class="modal-body">	
	<?php if($accountsStatus->value == 1){ ?>
		<div class="">
			<div><h4>可請款收益(含稅)</h4></div>
			<div class="cash-text"><strong>$<?php echo number_format(tax($this->supplier->type,$count_monies - $deductAccounts), 0, "." ,",");?></strong></div>			
		</div>
	<?php }else{?>
		<div>目前尚未開放申請，如有需要請向您的窗口詢問</div>
	<?php }?>
</div>
<div class="modal-footer">
	<!-- <button type="button" class="btn btn-primary application-btn">申請支付款項</button> -->
	<?php if($accountsStatus->value == 1){ ?>
		<a href="payments?type=applicationPay" class="btn btn-primary btn-xl">申請支付款項</a>
	<?php }?>
	<button type="button" class="btn btn-default close-btn" data-dismiss="modal">關閉</button>
</div>
