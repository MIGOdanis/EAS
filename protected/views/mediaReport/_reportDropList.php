<div class="dropdown">
	<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
		<?php echo $defReport; ?>
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
		<li>
		<?php $this->widget('UIAuthWidget', array(
			'checkType'=>"auth",
			'auth'=>array("mediaReport/mediaReport/supplierReport"),
			'user'=>$this->user,
			'html'=>'<a href="supplierReport">供應商日報表</a>'
		)); ?>		
		</li>
		<li>
		<?php $this->widget('UIAuthWidget', array(
			'checkType'=>"auth",
			'auth'=>array("mediaReport/mediaReport/siteReport"),
			'user'=>$this->user,
			'html'=>'<a href="siteReport">網站日報表</a>'
		)); ?>		
		</li>
		<li>
		<?php $this->widget('UIAuthWidget', array(
			'checkType'=>"auth",
			'auth'=>array("mediaReport/mediaReport/adSpaceReport"),
			'user'=>$this->user,
			'html'=>'<a href="adSpaceReport">版位日報表</a>'
		)); ?>		
		</li>
	</ul>
</div>