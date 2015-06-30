<style type="text/css">
	#contents{
		max-width: 450px;
		min-width: 350px;
		margin-left: auto;
		margin-right: auto;
		/*padding-top: 50px;*/
	}
</style>
<div class="page-header">
  <h3><span class="glyphicon glyphicon-user" aria-hidden="true"></span> 登入</h3>
</div>
<?php $this->renderPartial('_login_form', array('model'=>$model)); ?>