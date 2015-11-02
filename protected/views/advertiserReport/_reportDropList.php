<div class="dropdown">
	<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
		<?php echo $defReport; ?>
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
		<li>
		<?php $this->widget('UIAuthWidget', array(
			'checkType'=>"auth",
			'auth'=>array("mediaReport/advertiserReport/categoryReport"),
			'user'=>$this->user,
			'html'=>'<a href="categoryReport">媒體分類報表</a>'
		)); ?>
		</li>
		<li>
		<?php $this->widget('UIAuthWidget', array(
			'checkType'=>"auth",
			'auth'=>array("mediaReport/advertiserReport/campaignBannerReport"),
			'user'=>$this->user,
			'html'=>'<a href="campaignBannerReport">廣告活動總表</a>'
		)); ?>		
		</li>
		<li>
		<?php $this->widget('UIAuthWidget', array(
			'checkType'=>"auth",
			'auth'=>array("mediaReport/advertiserReport/ytbReport"),
			'user'=>$this->user,
			'html'=>"<a href=\"ytbReport\">影音廣告報表</a>"
		)); ?>
		</li>
		<li>
		<?php $this->widget('UIAuthWidget', array(
			'checkType'=>"auth",
			'auth'=>array("mediaReport/advertiserReport/functionReport"),
			'user'=>$this->user,
			'html'=>'<a href="functionReport">加值功能報表</a>'
		)); ?>				
		</li>
	</ul>
</div>


