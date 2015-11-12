<?php
$this->beginContent('/layouts/supplier_main');
?>
<div id="left-main">
	<div id="menu-list-group">
		<?php 
		if($this->action->id == "mySite"){
		?>
		<div class="menu-list report-list applySite"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 申請新網站</div>
		<?php 
		}
		$r = 0;
		foreach ($this->site as $site) {
			$r++;
		?>
			<div class="menu-list report-site-list report-list <?php if($r == 1){ echo 'report-site-active'; }?>" data-site="<?php echo $site->tos_id;?>"><?php echo $site->name;?></div>
			<?php foreach ($site->adSpace as $adSpace) {?>
				<div class="menu-list report-adspace-list report-list site-<?php echo $site->tos_id;?>" data-site="<?php echo $site->tos_id;?>" data-adSpace="<?php echo $adSpace->tos_id;?>"><?php echo $adSpace->name;?></div>
			<?php }?>			
		<?php }?>
	</div>
</div>
<div id="right-main">
	<?php echo $content; ?>
</div>
<?php $this->endContent(); ?>