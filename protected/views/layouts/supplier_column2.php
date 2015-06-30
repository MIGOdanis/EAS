<?php
$this->beginContent('/layouts/supplier_main');
?>
<div id="left-main">
	<div id="menu-list-group">
		<?php if (in_array($this->action->id, array("payments"))) {?>
			<div class="menu-list"><a href="payments">請款資訊</a></div>
			<div class="menu-list">帳務資訊(未開放)</div>
			<div class="menu-list">請款紀錄(未開放)</div>
		<?php }?>

	</div>
</div>
<div id="right-main">
	<?php echo $content; ?>
</div>
<?php $this->endContent(); ?>