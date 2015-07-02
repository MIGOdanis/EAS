<div class="page-header">
  <h1>供應商管理</h1>
  <small>資料時間 <?php echo date("Y-m-d H:i", $lastSync->value); ?></small>
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
function tableEvent(){
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
}
tableEvent();
");
?>
<form>
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
</p>
</form>
<?php 
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
					<li><a href="supplierUserList?id=' . $data->id . '">檢視帳號</a></li>
					<li><a href="supplierUserCreate?id=' . $data->id . '" class="set-btn" >建立帳號</a></li>
					<li role="separator" class="divider"></li>
					<li><a href="updateLog?id=' . $data->id . '">查詢修改記錄</a></li>
					<li role="separator" class="divider"></li>
					<li><a href="gotoDashboard?id=' . $data->id . '">前往模擬前台</a></li>					
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
	'ajaxUpdate'=>true,
	'afterAjaxUpdate'=>'tableEvent',
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
			'name'=>'status',
			'value'=>'($data->status == 1)? "啟用中" : "停用中"',
			'htmlOptions'=>array('width'=>'90'),
			'filter'=>false,
		),									
		array(
			'header'=>'檢視',
			'type'=>'raw',
			'value'=> 'setting($data)',
			'htmlOptions'=>array('width'=>'40')
		),
	),
));
?>