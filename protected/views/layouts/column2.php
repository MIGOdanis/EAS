<?php
$this->beginContent('/layouts/main');
?>
<div id="contents">
	<div id="left-main">
		<div class="list-group">
			<!-- <a href="#" class="list-group-item disabled">Cras justo odio</a> -->
			<?php
			foreach ($this->nav as $navIndex => $value) {
				if(in_array($this->id, $value['controllers'])){
					foreach ($value['list'] as $listIndex => $list) {
						$auth = json_decode($this->user->auth->auth,true);
						$authIndex = array();
						foreach ($auth[$navIndex] as $key => $authList) {
							$authIndex[] = $key;
						}
						//print_r($auth[$navIndex]); exit;
						if(Yii::app()->user->id && in_array($listIndex, $authIndex)){
					?>
						<a href="<?php echo Yii::app()->createUrl($list["url"]); ?>" class="list-group-item <?php if($this->id == $listIndex){?> disabled <?php }?>"><?php echo $list['title'];?></a>
					<?php 
						}
					}

					//跳出
					break;
				}
			}
			?>
		</div>
		<div class="keys-alert"><small><span class="glyphicon glyphicon-tag" aria-hidden="true"></span>使用Shift + Q快速收合選單</small></div>
	</div>
	<div id="right-main">
		<?php echo $content; ?>
	</div>
</div>
<?php $this->endContent(); ?>