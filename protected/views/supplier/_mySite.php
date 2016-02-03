<style type="text/css">
	.download-btn{
		margin-top: 5px;
	}
</style>
<script type="text/javascript">
$('.applySite').click(function() {
	$.ajax({
		url:"applySite?id=<?php echo $this->supplier->tos_id;?>",
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
</script>
<div class="page-header">
	<h1>網站總覽</h1>
<!-- 	<h6>申請與異動網站版位資訊，請洽您的專員！</h6>
 -->	
 	<button type="button" class="btn btn-primary applySite">申請新網站</button>
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
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'yiiCGrid',
	'itemsCssClass' => 'table table-bordered table-striped',
	'dataProvider'=>$model->getSupplierSiteList($this->supplier->tos_id),
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
			'header'=>'網站ID',
			'value'=>'$data->tos_id',
			'htmlOptions'=>array('width'=>'90'),
		),			
		array(
			'header'=>'網站名稱',
			'name'=>'name',
			'type'=>'raw',
			'value'=> '$data->name',
		),			
		array(
			'name'=>'type',
			'type'=>'raw',
			'value'=>'$data->category->mediaCategory->name',
			'htmlOptions'=>array('width'=>'90'),
		),			
		array(
			'name'=>'domain',
			'type'=>'raw',
			'value'=>'$data->domain',
			// 'htmlOptions'=>array('width'=>'180'),
		),				
		array(
			'name'=>'type',
			'type'=>'raw',
			'value'=>'Yii::app()->params["siteType"][$data->type]',
			'htmlOptions'=>array('width'=>'120'),
		),
	),
));
?>
