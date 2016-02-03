<?php
$this->beginContent('/layouts/supplier_main');
?>
<div id="left-main">
	<div id="menu-list-group">
		<?php 
		if($this->action->id == "mySite"){
		?>
		<!-- <div class="menu-list report-list applySite"></span>網站總覽</div> -->
		<div class="menu-list report-list siteList"></span>網站總覽</div>
		<?php 
		}

		$siteList = array();
		foreach ($this->site as $site) {
			$siteList[$site->type][] = $site;
		}

		$r = 0;

		if(!empty($siteList[1])){
		?>
			<div class="report-site-list-type-page-header"><div class="report-site-list-type">PC</div></div>

			<?php
			foreach ($siteList[1] as $site) {
				$r++;
			?>
				<div class="menu-list report-site-list report-list <?php if($r == 1){ echo 'report-site-active'; }?>" data-site="<?php echo $site->tos_id;?>"><?php echo $site->name;?></div>
				<?php foreach ($site->adSpace as $adSpace) {?>
					<div class="menu-list report-adspace-list report-list site-<?php echo $site->tos_id;?>" data-site="<?php echo $site->tos_id;?>" data-adSpace="<?php echo $adSpace->tos_id;?>"><?php echo $adSpace->name;?></div>
				<?php }?>			
			<?php }
		}

		if(!empty($siteList[3])){

		?>
			<div class="report-site-list-type-page-header"><div class="report-site-list-type">Mobile WEB</div></div>
			<?php 
			foreach ($siteList[3] as $site) {
				$r++;
			?>
				<div class="menu-list report-site-list report-list <?php if($r == 1){ echo 'report-site-active'; }?>" data-site="<?php echo $site->tos_id;?>"><?php echo $site->name;?></div>
				<?php foreach ($site->adSpace as $adSpace) {?>
					<div class="menu-list report-adspace-list report-list site-<?php echo $site->tos_id;?>" data-site="<?php echo $site->tos_id;?>" data-adSpace="<?php echo $adSpace->tos_id;?>"><?php echo $adSpace->name;?></div>
				<?php }?>			
			<?php }
		}

		if(!empty($siteList[2])){
		?>
			<div class="report-site-list-type-page-header"><div class="report-site-list-type">Mobile APP</div></div>
			<?php
			foreach ($siteList[2] as $site) {
				$r++;
			?>
				<div class="menu-list report-site-list report-list <?php if($r == 1){ echo 'report-site-active'; }?>" data-site="<?php echo $site->tos_id;?>"><?php echo $site->name;?></div>
				<?php foreach ($site->adSpace as $adSpace) {?>
					<div class="menu-list report-adspace-list report-list site-<?php echo $site->tos_id;?>" data-site="<?php echo $site->tos_id;?>" data-adSpace="<?php echo $adSpace->tos_id;?>"><?php echo $adSpace->name;?></div>
				<?php }?>			
			<?php }
		}?>
	</div>
</div>
<div id="right-main">
	<?php echo $content; ?>
</div>
<?php $this->endContent(); ?>