<div class="page-header">
  <h1>Hi! Welcome</h1>
</div>
<style type="text/css">
	#content-singe{
		width: 100%;
	}
</style>
<script type="text/javascript">
$(function(){
	$('.viewMessage').click(function() {
		var url = $(this).prop('href');
		$.ajax({
			url:url,
			success:function(html){
				$('#modal-content-lg').html(html);
				$('#modal-lg').modal('show');
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
	'dataProvider'=>$model->supplierMessage(),
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
			'name'=>'title',
			'type'=>'raw',
			'value'=>'CHtml::link($data->title,array("index/messageView","id"=>$data->id),array("class" => "viewMessage"))',
		),	
		array(
			'header' => '發布時間',
			'name'=>'publish_time',
			'type'=>'raw',
			'value'=>'date("Y-m-d H:00",$data->publish_time)',
			'htmlOptions'=>array('width'=>'140'),
		)										
	),
));
?>