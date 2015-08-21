<div class="page-header">
  <h1>供應商電子合約管理</h1>
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
<form>
<!-- <div class="panel panel-default">
	<div class="panel-heading">篩選</div>
	<div class="panel-body"> -->
		<p>
			供應商身分
			<select name="type" class="select-type">
				<option value="" <?php if(!isset($_GET['type'])){ ?>selected="selected"<?php }?>>全部</option>
				<option value="1" <?php if($_GET['type'] == 1){ ?>selected="selected"<?php }?>>無資料</option>
				<option value="2" <?php if($_GET['type'] == 2){ ?>selected="selected"<?php }?>>國內個人</option>
				<option value="3" <?php if($_GET['type'] == 3){ ?>selected="selected"<?php }?>>國外個人</option>
				<option value="4" <?php if($_GET['type'] == 4){ ?>selected="selected"<?php }?>>國內公司</option>
				<option value="5" <?php if($_GET['type'] == 5){ ?>selected="selected"<?php }?>>國外公司</option>
			</select>
			<button type="submit" class="btn btn-primary btn-sm">篩選</button>
			<!-- <a href="admin" class="btn btn-warning btn-sm">清除條件</a> -->
		</p>
<!-- 	</div>
</div> -->
</form>
<!-- <a href="register" class="btn btn-default set-btn">申請供應商</a> -->
<?php 
function supplierType($data){
	$types = array("未填","國人", "外人", "國司", "外司");
	$class = array("danger","default", "primary", "success", "warning");

	return '<span class="label label-' . $class[$data->type] . '">' . $types[$data->type] . '</span>';
}
function check($data){
	$checks = array("未填寫", "待審核", "退回修改", "已核准", "審核未過", "資料補充申請" , "補充待審核", "補充退回修改", "補充資料已核准");
	return $checks[$data->check];	
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
		array(
			'name'=>'name',
			'type'=>'raw',
			'value'=> 'supplierType($data) . CHtml::link($data->name,array("tosSite/admin","supplier_id"=>$data->tos_id))',
			'htmlOptions'=>array('width'=>'290'),
		),		
		array(
			'name'=>'company_name',
			'value'=>'$data->company_name',
			'htmlOptions'=>array('width'=>'290'),
		),				
		array(
			'name'=>'tel',
			'type'=>'raw',
			'value'=>'$data->contacts . "<br>" . $data->tel',
			'htmlOptions'=>array('width'=>'110'),
			'filter'=>false,
		),						
		array(
			'name'=>'check',
			'value'=>'check($data)',
			'htmlOptions'=>array('width'=>'90'),
			'filter'=>false,
		),									
		array(
			'header'=>'檢視',
			'type'=>'raw',
			'value'=> 'CHtml::link("檢視",array("supplierRegister/view","id"=>$data->id),array("class"=>"btn btn-default set-btn"))',
			'htmlOptions'=>array('width'=>'55')
		),
	),
));
?>