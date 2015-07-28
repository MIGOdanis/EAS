<style type="text/css">
	.download-btn{
		margin-top: 5px;
	}
</style>
<div class="page-header">
	<h1><?php echo $model->name;?></h1>
	<h6>申請與異動網站版位資訊，請洽您的專員！</h6>
	<h6>網站類型 : <?php echo Yii::app()->params["siteType"][$model->type];?></h6>
	<?php if($model->type == 2){ ?>

		<?php if(Yii::app()->params["androidSdkVersionNowNew"] || Yii::app()->params["iosSdkVersionNowNew"]){ ?>
			<div class="alert alert-success" role="alert">
				<?php if(Yii::app()->params["androidSdkVersionNowNew"]){ ?>
					<div>
						最新版本的 Android SDK <?php echo Yii::app()->params["androidSdkVersion"];?> 現已開放下載!!
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
		<!-- <a target="_blank" href="<?php echo Yii::app()->params["iosSdkUrl"];?>" class="btn btn-primary download-btn">IOS SDK (V <?php echo Yii::app()->params["iosSdkVersion"];?>)</a> -->
		<!-- <a target="_blank" href="<?php echo Yii::app()->params["iosSdkDoc"];?>" class="btn btn-primary download-btn">IOS SDK Document</a> -->
	<?php }?>
</div>
<?php 
if(isset($model->adSpace) && !empty($model->adSpace)){
	foreach ($model->adSpace as $value) {?>
		<div class="display-supplier-adSpace">
			<h4><?php echo $value->name;?></h4>

			<h5>版位大小 : <?php echo ($model->type == 1) ? $value->width . " x " . $value->height : str_replace (":"," x ",$value->ratio_id);?></h5>

			<h5>拆分方式 : <?php echo Yii::app()->params['buyType'][$value->buy_type]; ?></h5>
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
				})
				</script>
			<?php }?>
		</div>
	<?php }
}
?>
