
<div class="page-header">
  <h1>資料輸出</h1>
</div>

<div class="alert alert-danger" role="alert">
  由於資料輸出皆使用線上資料庫，如遇無法下載請過10分鐘再試! 切勿狂按下載按鈕!!
</div>
		<?php $this->widget('UIAuthWidget', array(
			'checkType'=>"auth",
			'auth'=>array("mediaReport/downloadData/siteAdSpaceInfor"),
			'user'=>$this->user,
			'html'=>'<a class="btn btn-default btn-lg" href="siteAdSpaceInfor" target="_new">網站版位資訊</a>'
		)); ?>
		<?php $this->widget('UIAuthWidget', array(
			'checkType'=>"auth",
			'auth'=>array("mediaReport/downloadData/strategyInfor"),
			'user'=>$this->user,
			'html'=>'<a class="btn btn-default btn-lg" href="strategyInfor" target="_new">訂單策略資訊</a>'
		)); ?>		
		<?php $this->widget('UIAuthWidget', array(
			'checkType'=>"auth",
			'auth'=>array("mediaReport/downloadData/creativeInfor"),
			'user'=>$this->user,
			'html'=>'<a class="btn btn-default btn-lg" href="creativeInfor" target="_new">訂單素材資訊</a>'
		)); ?>
		<?php $this->widget('UIAuthWidget', array(
			'checkType'=>"auth",
			'auth'=>array("mediaReport/downloadData/creativeInfor"),
			'user'=>$this->user,
			'html'=>'<a class="btn btn-default btn-lg" href="creativeInfor" target="_new">訂單素材資訊</a>'
		)); ?>		
<p></p>