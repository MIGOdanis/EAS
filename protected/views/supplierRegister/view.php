<style type="text/css">
	.table-header{
		font-weight: bold;
		text-align: right;
		width: 20%;
	}
	th{
		width: 150px;
	}
</style>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel"><?php echo $model->name;?></h4>
</div>
<div class="modal-body">
<p>基本資料</p>
<?php 
$types = array("無資料","台灣個人", "國外個人", "台灣公司", "國外公司");
$bank = array("國外銀行","國內銀行");
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay' => '未填寫',
	'htmlOptions' => array('class'=>'table table-bordered'),
	'attributes'=>array(
		'tos_id',
		'country_code',
		'name',
		'tel',
		'email',
		'company_name',
		'company_address',
		'mail_address',
		array(
			'name' => '供應商類型',
			'value' => $types[$model->type],
		),	
		array(
			'name' => '營利登記證 / 身份證 / 護照',
			'type' => 'raw',
			'value' => (empty($model->certificate_image)) ? "無資料" : '<img src="' . Yii::app()->params['baseUrl'] . "/upload/registerSupplier/" . $model->certificate_image . '" alt="certificate_image" class="img-thumbnail">',
		),			
		array(
			'name' => '資料建立時間',
			'value' => (empty($model->create_time)) ? "未填寫" : date("Y-m-d H:i",$model->create_time),
		),				
	),
)); ?>	

<p>聯絡人資料</p>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay' => '未填寫',
	'htmlOptions' => array('class'=>'table table-bordered'),
	'attributes'=>array(
		'contacts',
		'contacts_email',
		'contacts_tel',
		'contacts_moblie',
		'contacts_fax',
	),
)); ?>
<p>帳務資料</p>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay' => '未填寫',
	'htmlOptions' => array('class'=>'table table-bordered'),
	'attributes'=>array(
		'account_name',
		'account_number',
		'bank_name',
		'bank_id',
		'bank_sub_name',
		'bank_sub_id',
		array(
			'name' => '銀行類型',
			'value' => $bank[$model->bank_type],
		),
		'bank_swift',
		'bank_swift2',
		array(
			'name' => '帳戶影本',
			'type' => 'raw',
			'value' => (empty($model->bank_book_img)) ? "無資料" : '<img src="' . Yii::app()->params['baseUrl'] . "/upload/registerSupplier/" . $model->bank_book_img . '" alt="certificate_image" class="img-thumbnail">',
		),		
	),
)); ?>
</div>
<div class="modal-footer">
	<?php if($model->check != 3): ?>
		<a class="btn btn-warning set-btn" href="check?id=<?php echo $model->id;?>&type=1">退回修改</a>
		<a class="btn btn-success set-btn" href="check?id=<?php echo $model->id;?>&type=2">審核通過</a>
	<?php endif; ?>
	<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
</div>