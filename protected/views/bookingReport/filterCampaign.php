<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel">訂單濾除</h4>
</div>
<script type="text/javascript">
	$(function(){
		var kbStatus = "";
		var videoStatus = "";
		var nopayStatus = "";
		var prStatus = "";
		var prnopay = "";

		$(".select-btn").click(function(){
			$(".checkbox").each(function(){
				$(this).prop("checked","true");
			})
		});

		$(".unSelect-btn").click(function(){
			$(".checkbox").each(function(){
				$(this).prop("checked","");
			})
		});

		$(".kb-btn").click(function(){
			if(kbStatus === true){
				kbStatus = "";
			}else{
				kbStatus = true;
			}			
			$(".checkbox").each(function(){
				var name = $(this).prop("title");
				
				if(name.indexOf("蓋板") > -1 || name.indexOf("蓋版") > -1){
					$(this).prop("checked",kbStatus);
				}
			})
		});

		$(".video-btn").click(function(){
			if(videoStatus === true){
				videoStatus = "";
			}else{
				videoStatus = true;
			}			
			$(".checkbox").each(function(){
				var name = $(this).prop("title");
				
				if(name.indexOf("(影音)") > -1){
					$(this).prop("checked",videoStatus);
				}
			})
		});

		$(".nopay-btn").click(function(){
			if(nopayStatus === true){
				nopayStatus = "";
			}else{
				nopayStatus = true;
			}			
			$(".checkbox").each(function(){
				var name = $(this).prop("title");
				
				if(name.indexOf("(墊檔)") > -1){
					$(this).prop("checked",nopayStatus);
				}
			})
		});

		$(".pr-btn").click(function(){
			if(prStatus === true){
				prStatus = "";
			}else{
				prStatus = true;
			}			
			$(".checkbox").each(function(){
				var name = $(this).prop("title");
				if(name.indexOf("(PR)") > -1 || name.indexOf("(pr)") > -1 || name.indexOf("(PR贈送)") > -1 || name.indexOf("(PR墊檔)") > -1){
					$(this).prop("checked",prStatus);
				}
			})
		});

		$(".prnopay-btn").click(function(){
			if(prnopay === true){
				prnopay = "";
			}else{
				prnopay = true;
			}			
			$(".checkbox").each(function(){
				var name = $(this).prop("title");
				if(name.indexOf("(PR墊檔)") > -1 || name.indexOf("(PR贈送)") > -1){
					$(this).prop("checked",prnopay);
				}
			})
		});


	})
</script>
<?php 
$noPayCampaignId = array();
if(isset($_COOKIE['noPayCampaignId']) && !empty($_COOKIE['noPayCampaignId'])){
	$noPayCampaignId = explode(":", $_COOKIE['noPayCampaignId']);
}
?>
<style type="text/css">
	.checkbox{
		width: 20px;
		height: 20px;
	}
	.modal-body{
		height: 600px;
		overflow: auto;
	}
</style>
<form action="" method="post">
	<input name="setCampaign" type="text" class="checkbox" value="1" style="display:none;">
	<div class="modal-body">	
		<table class="table table-bordered">
		<thead>
			<th>排除</th>
			<th>訂單編號</th>
			<th>訂單名稱</th>
		</thead>
		<tbody>
		<?php foreach ($model as $value) { ?>
			<tr>
			<td><input type="checkbox" class="checkbox" title="<?php echo $value->campaign->campaign_name; ?>" value="<?php echo $value->campaign->tos_id;?>" name="noPayCampaignId[]" <?php echo (in_array($value->campaign->tos_id, $noPayCampaignId)) ? "checked" : ""; ?>></td>
			<td><?php echo $value->campaign->tos_id; ?></td>
			<td><?php echo $value->campaign->campaign_name; ?></td>
			<tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-primary prnopay-btn">選PR墊檔</button>
		<button type="button" class="btn btn-primary pr-btn">選PR</button>
		<button type="button" class="btn btn-primary nopay-btn">選墊檔</button>
		<button type="button" class="btn btn-primary video-btn">選影音</button>
		<button type="button" class="btn btn-primary kb-btn">選蓋板</button>
		<button type="button" class="btn btn-primary select-btn">全選</button>
		<button type="button" class="btn btn-primary unSelect-btn">全不選</button>
		<br>
		<br>
		<input type="submit" class="btn btn-success" value="更新" />
		<a href="?resetFilter=1&day=<?php echo $_GET['day'];?>" class="btn btn-danger close-btn">重設為預設值</a>
		<button type="button" class="btn btn-warning close-btn" data-dismiss="modal">關閉</button>
	</div>
</form>