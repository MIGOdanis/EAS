<div class="page-header">
  <h1>供應商請款管理<?php echo ($accountsStatus->value == 1) ? "<span class='label label-success'>開帳中</span>" : "<span class='label label-danger'>關帳中</span>"; ?></h1>
  <small>資料時間 <?php echo date("Y-m-d H:i", $lastSync->value); ?></small>
</div>
<script type="text/javascript">
$(function() {
	$(".set-btn").live("click",function() {
		if(confirm("請確認是否備妥相關資料?")){
			var url = $(this).prop("href");
			$.ajax({
				url:url,
				dataType:"json",
				success:function(data){
					if(data.code == 1){
						alert("請款申請完成");
						window.location.reload();
					}else{
						alert(data.msg);
					}
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
		}
		return false;//阻止a标签		
	});				
})
</script>
<?php
function checkApplicationType($application_type,$checks){
	if($application_type === $checks){
		return 1;
	}else{
		return 0;
	}
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
?>
<style type="text/css">
	.select-type{
		height: 30px;
	}
	.dropdown-menu{
		right: 0px;
		left: auto;
	}
</style>
<form method="get">
<p>
	下載供應商對帳表 (本期:<?php echo date("Y / m",$monthOfAccount->value)?>)
	<select name="year" class="select-type">
	<?php for($y=2015; $y <= date("Y"); $y++) {?>
		<option value="<?php echo $y?>" 
			<?php if((isset($_GET['year']) && $_GET['year'] == $y) || (!isset($_GET['year']) && date("Y") == $y)){ ?>
				selected="selected"<?php }?>>
			<?php echo $y?>
		</option>
	<?php }?>
	</select>
	<select name="month" class="select-type">
	<?php for($m=1; $m <= 12; $m++) {?>
		<option value="<?php echo $m?>" 
			<?php if((isset($_GET['month']) && $_GET['month'] == $m) || (!isset($_GET['month']) && date("m") == $m)){ ?>
				selected="selected"<?php }?>>
			<?php echo $m?>
		</option>
	<?php }?>
	</select>
	<input type="hidden" name="export" value="1">
	<button type="submit" class="btn btn-primary btn-sm">篩選</button>
</p>
</form>
<?php 
function application($data,$accountsStatus){
	if($accountsStatus == 0){
		return "關帳中 <br>";
	}else{
		return ($data->application_type == 1) ? 
		"請款中" :
		CHtml::link(
		 	"請款",
		 	array(
		 		"supplierApplicationMonies/application",
		 		"id"=>$data->supplier_id
		 	),
		 	array(
		 		"class"=>"btn btn-default set-btn"
		 	)
		);
	}
}

function tax($data,$floor){
	$tax = Yii::app()->params['taxType'][$data->site->supplier->type];
	if($data->site->supplier->type == 1 && $data->count_monies < 20000)
		$tax = 1;

	return number_format($data->count_monies * $tax, $floor, "." ,",");
	
}


function supplierType($data){
	$types = array("未填","國人", "外人", "國司", "外司");
	$class = array("danger","default", "primary", "success", "warning");

	return '<span class="label label-' . $class[$data->site->supplier->type] . '">' . $types[$data->site->supplier->type] . '</span>';
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
			'name'=>'site.supplier.tos_id',
			'value'=>'$data->site->supplier->tos_id',
			'htmlOptions'=>array('width'=>'90'),
		),	
		array(
			'name'=>'supplier_id',
			'type'=>'raw',
			'value'=>'supplierType($data) . $data->site->supplier->name',
			'htmlOptions'=>array('width'=>'240'),
		),	
		array(
			'header'=>'前期累計',
			'name'=>'total_monies',
			'htmlOptions'=>array('width'=>'130'),
			'value'=>'number_format($data->total_monies, 2, "." ,",")',
			'filter'=>false,
		),
		array(
			'header'=>'本月新增',
			'name'=>'month_monies',
			'htmlOptions'=>array('width'=>'130'),
			'value'=>'number_format($data->month_monies, 2, "." ,",")',
			'filter'=>false,
		),				
		array(
			'header'=>'稅前總額',
			'name'=>'count_monies',
			'htmlOptions'=>array('width'=>'130'),
			'value'=>'number_format($data->count_monies, 2, "." ,",")',
			'filter'=>false,
		),	
		array(
			'header'=>'稅後總額(原始)',
			'htmlOptions'=>array('width'=>'130'),
			'value'=>'tax($data,2)',
			'filter'=>false,
		),		
		array(
			'header'=>'稅後總額(進位)',
			'htmlOptions'=>array('width'=>'130'),
			'value'=>'tax($data,0)',
			'filter'=>false,
		),					
		array(
			'header'=>'請款月份(起)',
			'name'=>'last_application',
			'value'=>'($data->last_application > 0) ? date("Y-m",$data->last_application) : date("Y-m")',
			'htmlOptions'=>array('width'=>'130'),
			'filter'=>false,
		),	
		array(
			'header'=>'請款月份(迄)',
			'name' => "this_application",
			'value'=>'date("Y-m",$data->this_application)',
			'htmlOptions'=>array('width'=>'130'),
			'filter'=>false,
		),									
		array(
			'header'=>'請款',
			'type'=>'raw',
			'value'=> 'application($data,' . $accountsStatus->value . ')',
			'htmlOptions'=>array('width'=>'71'),
		),
	),
));

?>