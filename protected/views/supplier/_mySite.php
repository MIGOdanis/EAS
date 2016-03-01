<style type="text/css">
	.download-btn{
		margin-top: 5px;
	}
	.read-btn{
		color: #428bca;
		cursor: pointer;
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
$(".read-btn").click(function(){
	$("#contract").modal();
	$('#contract').modal('show');
});
	$(".set-font").click(function(){
		var size = $(this).data("size");
		console.log(size);
		$("#contract_body span").css("font-size",size);
	});
</script>
<div class="page-header">
	<h1>網站總覽</h1>
<!-- 	<h6>申請與異動網站版位資訊，請洽您的專員！</h6>
 -->	
 	<h5>請遵守域動廣告聯播網之<span class="read-btn">「網站合作合約條款」</span>，違反規定者，域動有權終止合作並拒絕支付廣告收益，感謝各位站長配合。</h5>
 	<button type="button" class="btn btn-primary applySite">申請新網站</button>
</div>
	<div class="modal fade" id="contract">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">				
					<h4 class="modal-title">域動行銷股份有限公司-網站合作銷售合約書</h4>
				</div>
				<div class="modal-body">
					<div>字形大小</div>
					<div class="btn-group" role="group" aria-label="">
						<button type="button" class="btn btn-default font-s-btn set-font" data-size="14px">小</button>
						<button type="button" class="btn btn-default font-m-btn set-font" data-size="16px">中</button>
						<button type="button" class="btn btn-default font-l-btn set-font" data-size="20px">大</button>
					</div>


					<?php echo $this->renderPartial('../registerSupplier/_contract'); ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">關閉</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
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
