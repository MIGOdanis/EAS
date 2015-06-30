<div class="page-header">
  <h1>查詢供應商修改資料</h1>
</div>
<style type="text/css">
	.select-type{
		height: 30px;
	}
	.dropdown-menu{
		right: 0px;
		left: auto;
	}
</style>
<script type="text/javascript">
$(function() {
				
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
$('.set-btn').click(function() {
	var url = $(this).prop('href');
	$.ajax({
		//type: 'POST',
		url:url,
		data: {pid:$(this).data('page')},
		async: false,
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
"); 
function supplierType($data){
	$types = array("未填","國人", "外人", "國司", "外司");
	$class = array("danger","default", "primary", "success", "warning");

	return '<span class="label label-' . $class[$data->type] . '">' . $types[$data->type] . '</span>';
}
function setting($data){

	return 	'<div class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true" aria-haspopup="true">
					操作 <span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li><a class="set-btn" href="view?id=' . $data->id . '">檢視</a></li>
					<li><a href="update?id=' . $data->id . '">修改</a></li>
					<li><a href="#">刪除</a></li>
					<li role="separator" class="divider"></li>
					<li><a href="#">檢視帳號</a></li>
					<li><a href="#">建立帳號</a></li>
					<li role="separator" class="divider"></li>
					<li><a href="updateLog?id=' . $data->id . '">查詢修改記錄</a></li>
				</ul>
			</div>';

			
}
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
		// array(
		// 	'header' => ''
		// 	'name'=>'o_id',
		// 	'value'=>'$data->o_id',
		// 	'htmlOptions'=>array('width'=>'90'),
		// ),			
		array(
			'name'=>'tos_id',
			'value'=>'$data->tos_id',
			'htmlOptions'=>array('width'=>'90'),
		),	
		'name',
		array(
			'name' => "update_time",
			'value'=>'date("Y-m-d H:i:s",$data->update_time)',
			'htmlOptions'=>array('width'=>'130'),
			'filter'=>false,
		),	
		array(
			'name' => "update_by",
			'value'=>'$data->updater->name',
			'htmlOptions'=>array('width'=>'130'),
			'filter'=>false,
		),													
		array(
			'header'=>'檢視',
			'type'=>'raw',
			'value'=> 'CHtml::link("檢視",array("tosSupplier/updateLogView","id"=>$data->id),array("class"=>"btn btn-default set-btn"))',
			'htmlOptions'=>array('width'=>'55')
		),
	),
));
?>