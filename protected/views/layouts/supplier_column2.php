<?php
$this->beginContent('/layouts/supplier_main');
?>
<style type="text/css">
#contents{
	overflow: hidden;
}	
</style>
<div id="left-main">
	<div id="menu-list-group">
		<?php if (in_array($this->action->id, array("payments","paymentSetting","paymentsHistroy"))) {?>
			<div class="menu-list <?php if ($this->action->id == "payments") {?> report-site-active <?php }?> "><a href="payments">請款</a></div>
			<div class="menu-list <?php if ($this->action->id == "paymentSetting") {?> report-site-active <?php }?> "><a href="paymentSetting">匯款資訊</a></div>	
			<div class="menu-list <?php if ($this->action->id == "paymentsHistroy") {?> report-site-active <?php }?> "><a href="paymentsHistroy">請款紀錄</a></div>
			<!-- <div class="menu-list">請款紀錄(未開放)</div> -->
		<?php }?>

	</div>
</div>
<div id="right-main">
	<?php echo $content; ?>
</div>
<?php $this->endContent(); ?>