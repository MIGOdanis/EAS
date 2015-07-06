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
  <h3><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>忘記密碼</h3>
</div>
<form name="resetPasswd" class="form-horizontal" id="resetPasswd" action="" method="post">
	<?php if($err):?>
	<div class="alert alert-danger" role="alert">
		<span class="sr-only">錯誤:</span>
		請確認您註冊使用的電子郵件
	</div>	
	<?php endif;?>
	<div class="form-group">
		<label class="">電子郵件</label>
		<div class="">
			<input class="form-control input-sm" data-name="電子郵件" required="required" name="mail" id="LoginForm_user" type="text">
		</div>
	</div>

	<div class="form-group login-button-group">
		<div class="col-md-offset-4 col-md-8 col-sm-offset-4 col-sm-8 text-right">
			<button id="resetPasswd" type="submit" class="btn btn-primary">確認</button>
		</div>
	</div>
</form>
