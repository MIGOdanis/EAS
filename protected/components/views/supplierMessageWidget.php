<a id="message-btn" href="#">
	<span class="glyphicon glyphicon-bullhorn <?php if($unRead > 0) { echo "message-active"; } ; ?> " aria-hidden="true"></span>
	<?php if($unRead > 0): ?>
		<div id="count-box">
			<?php 
				if($unRead > 99){
					echo "99+";
				}else{
					echo $unRead;
				}
			?>
		</div>
	<?php endif; ?>
</a>
<div id="message-group">
	<div class="arrow">
		<img src="<?php echo Yii::app()->params['baseUrl']; ?>/assets/image/arrow.png">
	</div>

	<?php if(count($model) > 0){ ?>
	<div id="message-top">
		<?php echo CHtml::link("查看更多",array("supplier/message")); ?> |
		<?php echo CHtml::link("設為已讀",array("supplier/setAllMessageRead"), array("class" => "setAllReadBtn")); ?>
	</div>	
	<div id="message-list-box">
		<?php 
		foreach ($model as $key => $value) { 
			$readStatus = $value->getReadStatus($value->id);
		?>
			<a href="<?php echo Yii::app()->createUrl("supplier/messageView",array("id"=>$value->id)); ?>" class="viewMessage">
				<div class="message-list <?php echo ($readStatus === null) ? "" : "message-read";?>">
					<div class="message-title">
						<?php echo $value->title; ?>
					</div>
					<div class="message-time">
						<?php echo (($readStatus === null) ? "(未讀取) " : "") . date("Y年m月d日", $value->publish_time);?>
					</div>
				</div>					
			</a>
		<?php }?>
	</div>
	<div id="message-view-all">
		<?php echo CHtml::link("查看更多",array("supplier/message")); ?>
	</div>
	<?php }else{?>
	<div id="message-view-all">
		沒有訊息
	</div>
	<?php }?>
</div>