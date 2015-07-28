<div class="page-header">
	<h1>上載電子合約 (<?php echo $model->name; ?>)</h1>
</div>
<style>
	.pdf-box{
		float: left;
		width: 150px;
		height: 250px;
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
	.pdf-uploadtime{
		text-align: center;
		border-bottom: solid 1px #CACACA;
	}
	.pdf-unactive{
		text-align: center;
		border-bottom: solid 1px #CACACA;
		padding-top: 5px;
		padding-bottom: 5px;
	}
	.gray-img { 
		-webkit-filter: grayscale(100%);
		-moz-filter: grayscale(100%);
		-ms-filter: grayscale(100%);
		-o-filter: grayscale(100%);
		filter: grayscale(100%);
		filter: gray;
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

<div class="pdf-box">
	<a href="getUploadContract?id=<?php echo $value->id; ?>" target="_blank">
		<div class="pdf-icon">
			<img height="120" <?php echo ($value->active == 0)? 'class="gray-img"' : ""; ?> src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/image/pdf-logo.png">
		</div>
	</a>
	<div class="pdf-unactive">
		<?php if($value->active){ ?>
			<a href="activeUploadContract?id=<?php echo $value->id; ?>" class="btn btn-primary btn-xs">廢棄此合約</a>
		<?php }else{ ?>
			(已廢棄)
		<?php }?>
	</div>
	<div class="pdf-uploadtime"><?php echo date("Y-m-d H:i",$value->time); ?></div>
	<div class="pdf-name">
		<a href="getUploadContract?id=<?php echo $value->id; ?>" target="_blank">
			<?php echo $value->file_name; ?>
		</a>
	</div>
</div>

<?php } ?>


