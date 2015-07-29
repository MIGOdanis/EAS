<div class="page-header">
  <h1>使用者管理</h1>
</div>
<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#yiiCGrid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="btn-group" role="group" aria-label="...">
	<div class="dropdown btn-group">
		<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			選擇群組
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
			<li><a href="admin">全部</a></li>
			<?php foreach (Yii::app()->params["userGroup"] as $key => $value) {?>
				<li><a href="admin?gid=<?php echo $key; ?>"><?php echo $value; ?></a></li>
			<?php }?>
		</ul>
	</div>	
	<a class="btn btn-default" href="create">新增使用者</a>
</div>



<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'yiiCGrid',
	'itemsCssClass' => 'table table-bordered table-striped',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'summaryText'=>'共 {count} 筆資料，目前顯示第 {start} 至 {end} 筆',
	'emptyText'=>'沒有資料',
	'pager' => array(
		'nextPageLabel' => '»',
		'prevPageLabel' => '«',
		'firstPageLabel' => ' ',
		'lastPageLabel'=> ' ',
		'header' => ' ',
		'htmlOptions' => array('class'=>'pagination'),
		'hiddenPageCssClass' => '',
		'selectedPageCssClass' => 'active',
		'previousPageCssClass' => '',
		'nextPageCssClass' => ''
	),
	'template'=>'{pager}{items}{pager}',
	'columns'=>array(
		array(
			'name'=>'user',
			'value'=>'$data->user',
		),		
		array(
			'name'=>'name',
			'value'=>'$data->name',
		),	
		array(
			'name'=>'group',
			'value'=>'Yii::app()->params["userGroup"][$data->group]',
			'htmlOptions'=>array('width'=>'120'),
			'filter'=>false,
		),
		array(
			'name'=>'last_login',
			'value'=>'($data->last_login > 0) ? date("Y-m-d H:i:s", $data->last_login) : "-"',
			'htmlOptions'=>array('width'=>'165'),
			'filter'=>false,
		),
		array(
			'name'=>'active',
			'value'=>'($data->active == 0)? "停用中" : "啟用中"',
			'htmlOptions'=>array('width'=>'90'),
			'filter'=>false,
		),									
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{activate}{deactivate}',
			'deleteConfirmation'=>"js:'確定是否要刪除此項目?'",
			'htmlOptions'=>array('width'=>'80'),
			'buttons'=>array
			(
				'activate'=>array(
						'label'=>'啟用',
						'url'=>'Yii::app()->createUrl("user/active", array("id"=>$data->id))',
						'click'=>"function() {
							if(!confirm('是否啟用?')) return false;
							var th = this,
								afterDelete = function(){};
							jQuery('#yiiCGrid').yiiGridView('update', {
								type: 'POST',
								url: jQuery(this).attr('href'),
								success: function(data) {
									jQuery('#yiiCGrid').yiiGridView('update');
									afterDelete(th, true, data);
								},
								error: function(XHR) {
									return afterDelete(th, false, XHR);
								}
							});
							return false;
						}",						
						'imageUrl'=> Yii::app()->params['baseUrl'] . '/assets/image/icon/layouts_icon_activate.jpg',
						'visible'=> '$data->active == 0',
				),
				'deactivate'=>array(
						'label'=>'停用',
						'url'=>'Yii::app()->createUrl("user/active", array("id"=>$data->id))',
						'click'=>"function() {
							if(!confirm('是否停用?')) return false;
							var th = this,
								afterDelete = function(){};
							jQuery('#yiiCGrid').yiiGridView('update', {
								type: 'POST',
								url: jQuery(this).attr('href'),
								success: function(data) {
									jQuery('#yiiCGrid').yiiGridView('update');
									afterDelete(th, true, data);
								},
								error: function(XHR) {
									return afterDelete(th, false, XHR);
								}
							});
							return false;
						}",
						'imageUrl'=> Yii::app()->params['baseUrl'] . '/assets/image/icon/poll_deactivate.jpg',
						'visible'=> '$data->active == 1',
				),			
			),
		),
	),
));
?>