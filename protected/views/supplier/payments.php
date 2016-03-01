<?php
function tax($type,$count_monies){
	$tax = Yii::app()->params['taxType'][$type];
	if($type == 1 && $count_monies < 20000)
		$tax = 1;

	return round($count_monies * $tax);
	
}

function unTax($type,$count_monies){
	$tax = Yii::app()->params['taxType'][$type];
	if($data->supplier->type == 1)
		$tax = 1;

	return round(tax($type,$count_monies) / $tax);
}

function status($status){
	$statusArra = array(
		"已退回",
		"<div style='color:red;'>申請中</div>",
		"<div style='color:red;'>憑證已確認</div>",
		"<div style='color:green;'>申請已完成</div>",
	);
	return $statusArra[$status];
}

$count_monies = $model->count_monies + $countYearAccounts;
?>
<script type="text/javascript">
	$(function(){
		$(".applicationPay").click(function(){
			$.ajax({
				url:"checkApplicationPay",
				success:function(html){
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
	})
</script>
<div id="payments">
	<div class="page-header">
		<?php if((empty($this->supplier->account_number) || empty($this->supplier->account_name)  || empty($this->supplier->bank_sub_id)  || empty($this->supplier->bank_id))){?>
			<div class="alert alert-danger" role="alert">您尚未填妥匯款資訊，請聯繫您的窗口! (電洽 <?php echo Yii::app()->params["cfTel"]?>)</div>
		<?php }?>
		<?php if($countYearAccounts > 0){ ?>
			<div class="alert alert-warning" role="alert">您前年度尚有款項累計 $<?php echo number_format($countYearAccounts, 0, "." ,",");?> 請您盡早請款! (此款項已包含在前期累計中)</div>
		<?php }?>		
		<h1>請款</h1>
	</div>
	<div>
		<div id="pay-infor">
			<div class="pay-infor-box">
				<div><h4>前期累計</h4></div>
				<div class="cash-text"><strong>$<?php echo number_format($model->total_monies + $countYearAccounts, 0, "." ,",");?></strong></div>			
			</div>

			<div class="pay-infor-box">
				<div><h4>　</h4></div>
				<div class="cash-text"><strong>+</strong></div>			
			</div>

			<div class="pay-infor-box">
				<div><h4>本月新增</h4></div>
				<div class="cash-text"><strong>$<?php echo number_format($model->month_monies, 0, "." ,",");?></strong></div>			
			</div>		

			<?php if($deductAccounts > 0): ?>

				<div class="pay-infor-box">
					<div><h4>　</h4></div>
					<div class="cash-text"><strong>-</strong></div>			
				</div>

				<div class="pay-infor-box">
					<div><h4>違規款項</h4></div>
					<div class="cash-text"><strong>$<?php echo number_format($deductAccounts, 0, "." ,",");?></strong></div>			
				</div>	

			<?php endif; ?>

			<div class="pay-infor-box">
				<div><h4>　</h4></div>
				<div class="cash-text"><strong>=</strong></div>			
			</div>

			<div class="pay-infor-box">
				<div><h4>可請款收益(未稅)</h4></div>
				<div class="cash-text"><strong>$<?php echo number_format(unTax($this->supplier->type,$count_monies - $deductAccounts), 0, "." ,",");?></strong></div>			
			</div>

			<div class="pay-infor-box">
				<div><h4>可請款收益(含稅)</h4></div>
				<div class="cash-text"><strong>$<?php echo number_format(tax($this->supplier->type,$count_monies - $deductAccounts), 0, "." ,",");?></strong></div>			
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
						<?php if($thisApplication->status == 3){ ?>
							<div>本期款項已經申請完成，感謝您的配合！</div>
						<?php }else{?>
							<div>本期款項已在申請作業中，請盡速提供您的相關文件加速作業！</div>
						<?php }?>
						<br>
						<div><h4>申請狀態</h4></div>
						<div><h4><strong><?php echo status($thisApplication->status);?></strong></h4></div>
				<?php }else{?>
					<?php if($count_monies > 0){?>
					<!-- <a href="payments?type=applicationPay" class="btn btn-primary btn-xl">申請支付款項</a> -->
							<button class="btn btn-primary btn-sm applicationPay">申請支付款項</button>
					<?php }else{?>
						<div>您沒有收益可供申請</div>
					<?php }?>
				<?php }?>
			<?php }else{?>
				<div>目前尚未開放申請，如有需要請向您的窗口詢問</div>
			<?php }?>
		</div>

		<br>
		<div>
			<h4>下載請款憑證</h4>
			<a href="payments?type=downloadIV" class="btn btn-primary btn-sm">下載</a>
		</div>

		<div>
			<h4>請款說明</h4>
			<br>
			<h5>請款須知</h5>
			<h5>1.請款時間為每月月初1-7號，系統將會寄發請款公告信通知站長請款，逾時不候。(如遇連續假期，將以每月公告為主)</h5>
			<h5>2.系統列出之「可請款收益(含稅)」中，尚未扣除匯款手續費，故站長帳戶中收到的金額會再扣除匯款手續費。</h5>
			<h5>3.請確認您的帳戶資訊是否正確。(付費→匯款資訊中可查看目前您所使用的匯款帳戶)</h5>
			<br>
			<h5>注意事項</h5>
			<?php if($this->supplier->type == 1):?>
				<h5>1. 依政府規定，中華民國國民執行業務所得(稿費、鐘點費)稅率10%，所得金額超過20,000元以上，應代扣稅款繳庫；故當月收益若達新台幣兩萬元以上，皆會自動扣除10%稅額。</h5>
				<h5>2. 個人戶請款須在支領申請單上確實簽名確認，無須另外附身分證影本。(支領申請單請登入後台後，從「付費」區塊下載。</h5>
				<h5>3.申領支領單確認簽名後，可掃描或拍照(照片需清晰)mail至您經常聯繫的窗口。</h5>
			<?php endif; ?>
			<?php if($this->supplier->type == 3):?>
				<h5>1. 請依照系統上之「可請款收益(含稅)」金額開立發票。</h5>
				<h5>2. 請注意發票是否填寫正確</h5>
				<h5>　發票抬頭: 域動行銷股份有限公司</h5>
				<h5>　統編: 24450379</h5>
				<h5>　品項: 廣告收入</h5>
				<h5>3. 發票寄送地址: 105 台北市松山區民生東路三段138號14樓-事業發展部收</h5>
			<?php endif; ?>
			<?php if($this->supplier->type == 2 || $this->supplier->type == 4):?>
				<h5>1.依政府規定，執行業務所得(稿費、鐘點費)稅率20%，無論所得金額多少，皆應代扣稅款繳庫。</h5>
				<h5>2.匯款手續費將會從收益中扣除5%手續費以及NT$440的匯費，此為台灣銀行收取之匯款費用。</h5>
			<?php endif; ?>			
		</div>

	</div>
</div>