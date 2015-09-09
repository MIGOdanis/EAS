<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel">訂單濾除</h4>
</div>
<script type="text/javascript">
	$(function(){
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
	<div class="modal-body">	
		<table class="table table-bordered">
		<thead>
			<th>排除</th>
			<th>訂單編號</th>
			<th>訂單名稱</th>
		</thead>
		<tbody>
		<?php foreach ($past as $value) { ?>
			<tr>
			<td><input type="checkbox" class="checkbox" value="<?php echo $value->campaign->tos_id;?>" name="noPayCampaignId[]" <?php echo (in_array($value->campaign->tos_id, $noPayCampaignId)) ? "checked" : ""; ?>></td>
			<td><?php echo $value->campaign->tos_id; ?></td>
			<td><?php echo $value->campaign->campaign_name; ?></td>
			<tr>
		<?php } ?>
		<?php foreach ($future as $value) { ?>
			<tr>
			<td><input type="checkbox" class="checkbox" value="<?php echo $value->campaign->tos_id;?>" name="noPayCampaignId[]" <?php echo (in_array($value->campaign->tos_id, $noPayCampaignId)) ? "checked" : ""; ?>></td>
			<td><?php echo $value->campaign->tos_id; ?></td>
			<td><?php echo $value->campaign->campaign_name; ?></td>
			<tr>
		<?php } ?>

		</tbody>
		</table>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default select-btn">全選</button>
		<button type="button" class="btn btn-default unSelect-btn">全不選</button>
		<input type="submit" class="btn btn-default" value="更新" />
		<a href="?resetFilter=1&day=<?php echo $_GET['day'];?>" class="btn btn-default close-btn">重設為預設值</a>
		<button type="button" class="btn btn-default close-btn" data-dismiss="modal">關閉</button>
	</div>
</form>