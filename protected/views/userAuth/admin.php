<div class="page-header">
  <h1>使用者權限管理</h1>
</div>
<script type="text/javascript">
$(function() {
	$(".set-btn").live("click",function() {
		var url = $(this).prop("href");
		$.ajax({
			//type: 'POST',
			url:url,
			data: { pid:$(this).data("page") },
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
			'header'=>'目前權限組',
			'name'=>'group',
			'value'=>'$data->auth->name',
			'htmlOptions'=>array('width'=>'120'),
			'filter'=>false,
		),		
		array(
			'header'=>'設定',
			'type'=>'raw',
			'value'=> 'CHtml::link("設定",array("userAuth/update","id"=>$data->id),array("class"=>"btn btn-default set-btn"))',
			'htmlOptions'=>array('width'=>'55')
		),										
	),
));
?>
