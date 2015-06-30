<div class="page-header">
  <h3>系統公告</h3>
</div>
<SCRIPT TYPE="text/javascript">	
	$(function() {
		$(".pager").attr("class"," ");
		$(".pagination .first,.pagination .last").hide();
	})
</SCRIPT>
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
	'dataProvider'=>$model->getMsgByTarget($target),
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
			'type' =>'raw',
			'value'=>'CHtml::link($data->title, array("index/view","id"=>$data->id,"target"=>$_GET["target"]), array("class"=>"viewMsg"))',
		),	
		// array(
		// 	'name'=>'publish_id',
		// 	'value'=>'$data->user->nick_name',
		// 	'htmlOptions'=>array('width'=>'100'),
		// ),	
		array(
			'name'=>'active_time',
			'value'=>'date("Y-m-d",$data->active_time)',
			'htmlOptions'=>array('width'=>'100'),
			'filter'=>false,
		),							
	),
)); ?>
<!-- Modal -->
<div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

</div>
<script type="text/javascript">
$( document ).ready(function() {
	$(".viewMsg").click(function() {
		var url = $(this).attr("href");
		$.ajax({
			type: 'POST',
			url:url,
			data: {ajax:1},
			success:function(html){
				$('#msgModal').html(html);
				$('#msgModal').modal('show');
			}
		})
        .fail(function(e) {
            if(e.status == 403){
                window.location.reload();
            }
        });
		return false;//阻止a标签		
	});
});
</script>