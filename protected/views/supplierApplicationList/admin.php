<div class="page-header">
  <h1>本期請款管理<?php echo ($accountsStatus->value == 1) ? "<span class='label label-success'>開帳中</span>" : "<span class='label label-danger'>關帳中</span>"; ?></h1>
</div>
<?php
function certificate_status($data,$accountsStatus){
	$option = array("data-id"=>$data->id,"class"=>"certificate_status", "id"=>"certificate_status_" . $data->id);
	if($data->status == 0){
		return "<div>此項目已被退回</div>";
	}elseif($data->status >= 3 || (($data->certificate_by != Yii::app()->user->id && $data->certificate_status > 0))){
		if($data->certificate_by != Yii::app()->user->id)
			$class = 'lock-of-user';

		if($data->status >= 3)
			$class = 'lock-of-invoice';

		return "<div class='" . $class . "'>" . $data->certificateChecker->name . "<br>已確認" . "</div>";
	}else{
		if($accountsStatus == 1){
			return CHtml::dropDownList("certificate_status",$data->certificate_status,array(
				"0"=> ($data->certificate_by == Yii::app()->user->id && $data->certificate_status > 0) ? "取消確認" : "未確認",
				"1"=> $data->certificateChecker->name . "已確認" 
			), $option);
		}else{
			return "關帳中";			
		}
	}
}

function status($data){
	$status = array(
		"已退回",
		"申請中",
		"憑證已確認",
		"申請已完成",
		//"款項已匯出"
	);
	return "<div id='status_" . $data->id ."'>" . $status[$data->status] . "</div>";
}

function invoice($data,$accountsStatus){

	if($data->certificate_status > 0 || $accountsStatus == 0){
		if($data->status == 0){
			$invoice = "此項目已被退回";
		}elseif($data->status >= 3){
			$invoice = $data->invoice 
						. "<br>" 
						. '<a class="btn btn-danger btn-xs invoice-reset" data-id="' . $data->id . '" data-invoice="' . $data->invoice . '">重填</a>　'
						. '<a class="btn btn-warning btn-xs invoice-view" data-id="' . $data->id . '">檢視</a>';
		
		}else{
			if($accountsStatus == 0){
				return '關帳中';
			}else{
				$invoice = '<input class="form-control invoice-input" data-id="' . $data->id . '" type="text" placeholder="發票號碼">';
			}
		}
		
	}else{
		$class = "invoice-alert";
		$invoice = "憑證未確認";
	}

	return "<div class='" . $class ."' id='invoice_" . $data->id ."'>" . $invoice . "</div>";
}

function tax($data){
	$tax = Yii::app()->params['taxType'][$data->supplier->type];
	if($data->supplier->type == 1 && $data->monies < 20000)
		$tax = 1;

	return number_format($data->monies * $tax, 0, "." ,",");
	
}

function taxDeductTot($data){
	$tax = tax($data);
	$taxDeduct = Yii::app()->params['taxTypeDeduct'][$data->supplier->type];
	if($data->supplier->type == 1 && $data->monies >= 20000)
		$taxDeduct = 0.9;

	return number_format($tax * $taxDeduct, $floor, "." ,",");
	
}

function taxDeduct($data){
	$tax = tax($data);
	$taxDeduct = taxDeductTot($data);

	return number_format($tax - $taxDeduct, $floor, "." ,",");
	
}

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});

$('.search-form form').submit(function(){
	refreshTable();
	return false;
});	

function tableEvent(){
	$('.certificate_status').change(function(e){
		var type = $(this).val();
		var id = $(this).data('id');
		var select = this;
		var data = {id:id,type:type};
		var url = 'certificate';

		$.post(url , data, function( data ) {
			if(type == 0){
				if (data.code == 1) {
					alert('已取消憑證確認');
				}else{
					alert('選取失敗，請按F5重新整理後再試');
				}
			}else{
				if (data.code == 1) {
					alert('已完成憑證確認');
				}else if(data.code == 3){
					alert('憑證已被確認');
				}else{
					alert('選取失敗，請按F5重新整理後再試');
				}
			}
			refreshTable();
		},'json')
		.fail(function(e) {
		    if(e.status == 403){
		        alert('權限不足');
		    }
		});
	});	

	$('.invoice-alert').click(function() {
		alert('請確認憑證是否已經完成確認!');
	});

	$('.lock-of-invoice').click(function() {
		alert('發票已填入，不可取消確認! \\n請先申請發票重設');
	});

	$('.lock-of-user').click(function() {
		alert('憑證已被確認');
	});

    $('.invoice-input').keydown(function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if (e.which == 13) {
        	var invoiceNum = $(this).val();
        	if(confirm('是否儲存發票號碼:\\n' + invoiceNum)){
        		var id = $(this).data('id');
        		var data = { id:id, invoiceNum:invoiceNum };
				$.post('invoice' , data, function( data ) {
					if(data.code == 1){
						alert('儲存成功');
						refreshTable();
					}else{
						alert('儲存失敗，請聯繫管理人員 #' + data.code);
					}
				},'json')
				.fail(function(e) {
				    if(e.status == 403){
				        alert('權限不足');
				    }
				});
        	}
        	return false;
        }
    });

    $('.invoice-reset').click(function() {
		var invoiceNum = $(this).data('invoice');
		if(confirm('請確認是否重設發票:\\n' + invoiceNum)){
			var id = $(this).data('id');
			var data = { id:id };
			$.post('invoiceReset' , data, function( data ) {
				if(data.code == 1){
					alert('重設成功');
					refreshTable();
				}else{
					alert('重設失敗，請聯繫管理人員 #' + data.code);
				}
			},'json')
			.fail(function(e) {
			    if(e.status == 403){
			        alert('權限不足');
			    }
			});
		}
		return false;
    });

	$('.invoice-view').click(function() {
		var id = $(this).data('id');
		$.ajax({
			type: 'POST',
			url:'invoiceView',
			data: {id:id},
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

    $('.sendback-btn').click(function() {
		if(confirm('請確認是否退回此筆申請?')){
			var id = $(this).data('id');
			var data = { id:id };
			$.post('sendBack' , data, function( data ) {
				if(data.code == 1){
					alert('退回成功');
					refreshTable();
				}else{
					alert('退回失敗，請聯繫管理人員 #' + data.code);
				}
			},'json')
			.fail(function(e) {
			    if(e.status == 403){
			        alert('權限不足');
			    }
			});
			
		}
		return false;   
    });

}

//更新表格
function refreshTable(){
	$('#yiiCGrid').yiiGridView('update', {
		data: $('#yiiCGrid').serialize()
	});	
};

tableEvent();
");
?>
<?php 
function supplierType($data){
	$types = array("未填","國人", "外人", "國司", "外司");
	$class = array("danger","default", "primary", "success", "warning");

	return '<span class="label label-' . $class[$data->supplier->type] . '">' . $types[$data->supplier->type] . '</span>';
}
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'yiiCGrid',
	'itemsCssClass' => 'table table-bordered table-striped',
	'ajaxUpdate'=>true,
	'afterAjaxUpdate'=>'tableEvent',
	'dataProvider'=>$model->search(true),
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
			'name'=>'supplier.tos_id',
			'value'=>'$data->supplier->tos_id',
			//'htmlOptions'=>array('width'=>'90'),
		),	
		array(
			'header'=>'供應商/申請日期',
			'name'=>'supplier_id',
			'type'=>'raw',
			'value'=>'supplierType($data) . $data->supplier->name ."<br>" . date("Y-m-d",$data->application_time) . "(" . $data->year . "-" . $data->month . ")"',
			'htmlOptions'=>array('width'=>'190'),
		),
		array(
			'name' => "start_time",
			'value'=>'date("Y-m",$data->start_time)',
			'htmlOptions'=>array('width'=>'80'),
			'filter'=>false,
		),
		array(
			'name' => "end_time",
			'value'=>'date("Y-m",$data->end_time)',
			'htmlOptions'=>array('width'=>'80'),
			'filter'=>false,
		),		
		array(
			'header'=>'請款總額(未稅)',
			'name' => "monies",
			'value'=>'"$" . number_format($data->monies, 2, "." ,",")',
			'htmlOptions'=>array('width'=>'100'),
			'filter'=>false,
		),
		array(
			'header'=>'請款總額(含稅)',
			'name' => "monies",
			'value'=>'"$" . tax($data)',
			'htmlOptions'=>array('width'=>'100'),
			'filter'=>false,
		),
		array(
			'header'=>'代扣稅額',
			'name' => "monies",
			'value'=>'"$" . taxDeduct($data)',
			'htmlOptions'=>array('width'=>'100'),
			'filter'=>false,
		),	
		array(
			'header'=>'應付總額',
			'name' => "monies",
			'value'=>'"$" . taxDeductTot($data)',
			'htmlOptions'=>array('width'=>'100'),
			'filter'=>false,
		),							
		array(
			'name' => "status",
			'type'=>'raw',
			'value'=>'status($data)',
			'htmlOptions'=>array('width'=>'90'),
			'filter'=>false,
		),		
		array(
			'type'=>'raw',
			'name'=>'certificate_status',
			'value'=>'certificate_status($data,' . $accountsStatus->value . ')',
			'filter'=>false,
		),	
		array(
			'type'=>'raw',
			'name'=>'invoice',
			'value'=>'invoice($data,' . $accountsStatus->value . ')',
		),															
		array(
			'header'=>'退回',
			'type'=>'raw',
			'value'=> '(' . $accountsStatus->value . ' == 1) ? CHtml::link("退回",array(),array("class"=>"btn btn-default sendback-btn","data-id" => $data->id)) : "關帳中"',
			'htmlOptions'=>array('width'=>'55'),
		),
	),
));

?>