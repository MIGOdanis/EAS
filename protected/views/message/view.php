<style type="text/css">
	.user_id_chk{
		float: left;
		border: solid 1px #ddd;
		padding: 5px;
		margin: 5px;
		border-radius: 5px;
	}
</style>

<div class="page-header">
  <h1><?php echo $model->title;?></h1>
</div>

<div class="panel panel-default">
	<div class="panel-heading">訊息內容</div>
	<div class="panel-body">
		<?php echo $model->body; ?>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">發布</div>
	<div class="panel-body">
		<p>發布時間 : <?php echo date("Y-m-d",$model->create_time); ?></p>
		<p>發布人 : <?php echo $model->creater->name; ?></p>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">期效</div>
	<div class="panel-body">
		<p>開始時間 : <?php echo date("Y-m-d H:00",$model->publish_time); ?></p>
		<p>結束時間 : <?php echo (($model->expire_time == 0) ? "無限期" : date("Y-m-d H:00",$model->create_time)); ?></p>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">郵件通知</div>
	<div class="panel-body">
		<p>預約發送 : <?php echo ($model->cron_mail == 0)? "否" : "是"; ?></p>
		<p>發送狀態 : <?php echo ($model->send_mail == 0)? "否" : "是"; ?></p>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">收信人</div>
	<div class="panel-body">
		<p>收信群組 : <?php echo Yii::app()->params["userGroup"][$model->user_group]; ?></p>
		<p>發送至 :</p>
		<div>
			<?php foreach ($user as $value) {?>
				<div class="user_id_chk">
					<?php echo $value->name;?>	
					<?php if($model->user_group == 7){?>
					(<?php echo $value->supplier->name;?>)
					<?php }?>
				</div>
			<?php }?>		
		</div>
	</div>
</div>