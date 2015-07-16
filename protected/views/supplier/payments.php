<?php
function tax($type,$count_monies){
	$tax = Yii::app()->params['taxType'][$type];
	if($type == 1 && $count_monies < 20000)
		$tax = 1;

	return number_format($count_monies * $tax, 0, "." ,",");
	
}

function status($status){
	$statusArra = array(
		"已退回",
		"申請中",
		"憑證已確認",
		"申請已完成",
	);
	return $statusArra[$status];
}
?>
<div class="page-header">
	<?php if((empty($this->supplier->account_number) || empty($this->supplier->account_name)  || empty($this->supplier->bank_sub_id)  || empty($this->supplier->bank_id))){?>
		<div class="alert alert-danger" role="alert">您尚未填妥匯款資訊，請聯繫您的窗口! (電洽 <?php echo Yii::app()->params["cfTel"]?>)</div>
	<?php }?>
	<h1>請款</h1>
</div>
<div>
	<div id="pay-infor">
		<div class="pay-infor-box">
			<div><h4>前期累計</h4></div>
			<div class="cash-text"><strong>$<?php echo number_format($model->total_monies, 0, "." ,",");?></strong></div>			
		</div>

		<div class="pay-infor-box">
			<div><h4>　</h4></div>
			<div class="cash-text"><strong>+</strong></div>			
		</div>

		<div class="pay-infor-box">
			<div><h4>本月新增</h4></div>
			<div class="cash-text"><strong>$<?php echo number_format($model->month_monies, 0, "." ,",");?></strong></div>			
		</div>		

		<div class="pay-infor-box">
			<div><h4>　</h4></div>
			<div class="cash-text"><strong>=</strong></div>			
		</div>

		<div class="pay-infor-box">
			<div><h4>可請款收益(未稅)</h4></div>
			<div class="cash-text"><strong>$<?php echo number_format($model->count_monies, 0, "." ,",");?></strong></div>			
		</div>

		<div class="pay-infor-box">
			<div><h4>可請款收益(含稅)</h4></div>
			<div class="cash-text"><strong>$<?php echo tax($this->supplier->type,$model->count_monies);?></strong></div>			
		</div>
	</div>
	<br>
	<?php if(!empty($lastApplication->id)){ ?>
		<div>前次請款月份為　<?php echo $lastApplication->year?>/<?php echo $lastApplication->month?></div>
	<?php }?>
	
	<br>
	<div id="application-group">
		<div><h4>申請款項</h4></div>
		<?php if($accountsStatus->value == 1){ ?>
			<?php if($model->application_type == 1){ ?>
				<div>本期款項已在申請作業中，請盡速提供您的相關文件加速作業！</div>
				<br>
				<div><h4>申請狀態</h4></div>
				<div><strong><h4><?php echo status($thisApplication->status);?></h4></strong></div>
			<?php }else{?>
				<?php if($model->count_monies > 0){?>
				<a href="payments?type=applicationPay" class="btn btn-primary btn-xl">申請支付款項</a>
				<?php }else{?>
					<div>您沒有收益可供申請</div>
				<?php }?>
			<?php }?>
		<?php }else{?>
			<div>目前尚未開放申請，如有需要請向您的窗口詢問</div>
		<?php }?>
	</div>

	<br>
	<div><h4>下載請款憑證</h4></div>
	<a href="payments?type=downloadIV" class="btn btn-primary btn-sm">下載</a>
	
</div>
