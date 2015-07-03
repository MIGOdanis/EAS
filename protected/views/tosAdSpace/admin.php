<div class="page-header">
  <h1>供應商網站版位管理</h1>
  <small>資料時間 <?php echo date("Y-m-d H:i", $lastSync->value); ?></small>
</div>
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
<!-- <a class="btn btn-default" href="create">新增使用者</a> -->
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
			'name'=>'tos_id',
			'value'=>'$data->tos_id',
			'htmlOptions'=>array('width'=>'90'),
		),	
		array(
			'name'=>'name',
			'value'=>'$data->name',
		),		
		array(
			'name'=>'site_id',
			'type'=>'raw',
			'value'=> 'CHtml::link($data->site->name,array("tosSite/admin","id"=>$data->site->tos_id))',
		),										
		array(
			'name'=>'status',
			'value'=>'($data->status == -1)? "停用中" : "啟用中"',
			'htmlOptions'=>array('width'=>'90'),
			'filter'=>false,
		),									
		array(
			'header'=>'檢視',
			'type'=>'raw',
			'value'=> 'CHtml::link("檢視",array("tosAdSpace/view","id"=>$data->id),array("class"=>"btn btn-default set-btn"))',
			'htmlOptions'=>array('width'=>'55')
		),
	),
));
?>