<style type="text/css">
	.download-btn{
		margin-top: 5px;
	}
</style>
<div class="page-header">
	<h1><?php echo $model->name;?></h1>
	<h3>版位放置文字說明:</h3>
	<h6>1.同一頁面中，不得同時放置兩組同一廣告代碼，會造成廣告失效，請直接點入下方「申請新版位」另外申請。</h6>
	<h6><h6>2.禁止惡意操作廣告之行為，若經查發現此情形，將扣除違規之款項，嚴重者當月款項皆不得請款。</h6>
	<h6>3.版位名稱開頭「CF」為PC端廣告代碼；「MW」為Mobile web廣告代碼；「MA」為App專用代碼。</h6>
	<h6>4.若需申請RWD響應式廣告代碼，請直接與域動服務團隊聯繫或mail： owen@clickforce.com.tw。</h6>
	<h6>5.禁止將廣告代碼任意放置在非審核過的網站中。</h6>
	<br>
	<h6>申請與異動網站版位資訊，請洽您的專員！</h6>
	<h6>網站類型 : <?php echo Yii::app()->params["siteType"][$model->type];?></h6>
	<button type="button" class="btn btn-primary applyAdSpace">申請新版位</button>
	<a href="http://eas.doublemax.net/assets/doc/GET_CODE.pdf" target="_blank" class="btn btn-primary">代碼取得說明</a>
	<?php if($model->type == 2){ ?>

		<?php if(Yii::app()->params["androidSdkVersionNowNew"] || Yii::app()->params["iosSdkVersionNowNew"]){ ?>
			<div class="alert alert-success" role="alert">
				<?php if(Yii::app()->params["androidSdkVersionNowNew"]){ ?>
					<div>
						最新版本的 Android SDK <?php echo Yii::app()->params["androidSdkVersion"];?> 現已開放下載!!<br><br>
						<p>Android SDK 1.17 修正以下 : <br> LOGO顯示的問題</p>
					</div>
				<?php }?>
				<?php if(Yii::app()->params["iosSdkVersionNowNew"]){ ?>
					<div>
						最新版本的 IOS SDK <?php echo Yii::app()->params["iosSdkVersion"];?> 現已開放下載!!
					</div>
				<?php }?>				
			</div>
		<?php }?>

		<a target="_blank" href="<?php echo Yii::app()->params["androidSdkUrl"];?>" class="btn btn-primary download-btn">Android SDK (V <?php echo Yii::app()->params["androidSdkVersion"];?>)</a>
		<a target="_blank" href="<?php echo Yii::app()->params["androidSdkDoc"];?>" class="btn btn-primary download-btn">Android SDK Document</a>
		<a target="_blank" href="<?php echo Yii::app()->params["iosSdkUrl"];?>" class="btn btn-primary download-btn">IOS SDK (V <?php echo Yii::app()->params["iosSdkVersion"];?>)</a>
		<a target="_blank" href="<?php echo Yii::app()->params["iosSdkDoc"];?>" class="btn btn-primary download-btn">IOS SDK Document</a>
	<?php }?>
</div>
<?php 
if(isset($model->adSpace) && !empty($model->adSpace)){
	foreach ($model->adSpace as $value) {?>
		<div class="display-supplier-adSpace">
			<h4><?php echo $value->name;?></h4>

			<h5><?php echo (($model->type == 2) ? "APP ID : " : "網站編號") . $value->site->tos_id;?></h5>
			<h5><?php echo (($model->type == 2) ? "Zone ID : " : "版位編號") . $value->tos_id;?></h5>
			<h5>版位大小 : <?php echo ($model->type == 1) ? $value->width . " x " . $value->height : str_replace (":"," x ",$value->ratio_id);?></h5>
			<h5>合作方式 : <?php echo Yii::app()->params['buyType'][$value->buy_type]; ?></h5>
			<h5>
			價格 : <?php echo Yii::app()->params['chrgeType'][$value->charge_type] . $value->price * Yii::app()->params['priceType'][$value->charge_type]; ?>
			<?php 
				if($value->buy_type == 2){
					echo "%";
				}
			?>
			</h5> 
			<?php if($model->type != 2){ ?>
				<button type="button" class="btn btn-primary code-btn" data-id="<?php echo $value->tos_id;?>">取得版位代碼</button>
			<?php }?>
			<script type="text/javascript">
			$(function(){
				$('.code-btn').click(function() {
					var id = $(this).data('id');
					$.ajax({
						url:"getAdSpaceCode",
						type:"get",
						data : { id : id },
						success:function(html){
							$('#modal-content').html(html);
							$('#modal').modal('show');
						}
					})
				    .fail(function(e) {
				        if(e.status == 403){
				        	alert('您的權限不足');
				            window.location.reload();
				        }
				        if(e.status == 500){
				        	alert('請稍後再試，或聯繫管理人員');
				        }            
				    });
					return false;//阻止a标签		
				});


				$('.applyAdSpace').click(function() {
					$.ajax({
						url:"applyAdSpace?id=<?php echo $value->site->id;?>",
						success:function(html){
							$('#modal-content').html(html);
							$('#modal').modal('show');
						}
					})
				    .fail(function(e) {
				        if(e.status == 403){
				        	alert('您的權限不足');
				            window.location.reload();
				        }
				        if(e.status == 500){
				        	alert('請稍後再試，或聯繫管理人員');
				        }            
				    });
					return false;//阻止a标签		
				});
			})
			</script>			
		</div>
	<?php }
}
?>
