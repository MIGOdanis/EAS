<div class="page-header">
  <h1>供應商網站申請管理</h1>
</div>
<style type="text/css">
	.select-type{
		height: 30px;
	}
</style>
<script type="text/javascript">
$(function() {
	$(".set-btn").live("click",function() {
		var url = $(this).prop("href");
		$.ajax({
			//type: 'POST',
			url:url,
			data: {pid:$(this).data("page")},
			async: false,
			success:function(html){
				$('#modal-content').html(html);
				$('#modal').modal('show');
			}
		})
        .fail(function(e) {
            if(e.status == 403){
            	alert("您的權限不足");
                window.location.reload();
            }
            if(e.status == 500){
            	alert("請稍後再試，或聯繫管理人員");
            }            
        });
		return false;//阻止a标签		
	});				
})
</script>
<?php

function getSiteType($type){
	return Yii::app()->params['siteType'][$type];
}

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

$this->widget('zii.widgets.grid.CGridView', array(
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
			'name'=>'supplier_id',
			'type'=>'raw',
			'value'=> 'CHtml::link($data->supplier->name,array("tosSupplier/admin","supplier_id"=>$data->supplier_id),array("target"=>"_new"))',
		),	
		array(
			'name'=>'name',
			'type'=>'raw',
			'value'=>'(!empty($data->url))? CHtml::link($data->name,array($data->url),array("target"=>"_new")) : $data->name',
			'filter'=>false,
		),	
		array(
			'name'=>'type',
			'value'=>'getSiteType($data->type)',
			'htmlOptions'=>array('width'=>'90'),
			'filter'=>false,
		),									
		array(
			'name'=>'status',
			'value'=>'($data->status == 1)? "申請中" : "已處理"',
			'htmlOptions'=>array('width'=>'90'),
			'filter'=>false,
		),									
		array(
			'header'=>'檢視',
			'type'=>'raw',
			'value'=> 'CHtml::link("檢視",array("tosSiteApply/view","id"=>$data->id),array("class"=>"btn btn-default set-btn"))',
			'htmlOptions'=>array('width'=>'55')
		),
	),
));
?>