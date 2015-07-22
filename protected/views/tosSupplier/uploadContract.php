<div class="page-header">
	<h1>上載電子合約 (<?php echo $model->name; ?>)</h1>
</div>
<style>
	.pdf-box{
		float: left;
		width: 150px;
		height: 180px;
		border: solid 1px #CACACA;
		margin-left: 20px;
		margin-top: 20px;
	}
	#right-main{
		overflow: hidden;
		padding-bottom: 10px;
	}
	.pdf-icon{
		margin-top: 10px;
		margin-left: 10px;
	}
	.pdf-name{
		background-color: #F6F6F6;
		width: 100%;
		height: 48px;
		padding: 5px;
		text-align: center;
		word-break: break-all;
	}
	.btn{
		margin-top: 10px;
	}
</style>


<form enctype="multipart/form-data" id="site-setting-form" action="" method="post">

<?php if($upload){ ?>
<div class="alert alert-<?php echo ($uploadChk)? "success" : "danger" ?>" role="alert"><?php echo $uploadMsg;?></div>
<?php } ?>
<label>上傳新合約 - 上傳20MB已內的PDF檔案 (大小超過可以分開上傳)</label>
<input class="image-upload" name="pdf" type="file">
<input class="btn btn-primary" type="submit" name="yt0" value="上傳">
</form>

<?php 
$upload_folder = Yii::app()->params['baseUrl'] . "/upload/SupplierContract/" . $model->tos_id;
foreach ($allData as $value) {
?>
<a href="<?php echo $upload_folder . "/" . $value->file_name; ?>" target="_blank">
<div class="pdf-box">
	<div class="pdf-icon">
		<img height="120" src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/image/pdf-logo.png">
	</div>
	<div class="pdf-name">
		<?php echo $value->file_name; ?>
	</div>
</div>
</a>
<?php } ?>


