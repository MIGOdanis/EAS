<style type="text/css">
	.table-header{
		font-weight: bold;
		text-align: right;
		width: 20%;
	}
</style>
<div class="modal-header">
	<h4 class="modal-title" id="myModalLabel"><?php echo $model->name;?></h4>
	<small>資料時間 <?php echo date("Y-m-d H:i", $model->sync_time); ?></small>
</div>
<div class="modal-body">
<?php 
$spaceType = array(
	"未知類型","固定","浮層","視頻貼片","視頻暫停","移動網頁","移動應用"
);
$adFormat = array(
	"10" => "固定",
	"11" => "ADTV",
	"12" => "浮窗",
	"13" => "視頻貼片",
	"14" => "擴展橫幅",
	"15" => "動態創意",
	//MOB
	"20" => "固定(WAP)",
	"21" => "視頻貼片(WAP)",
	"22" => "無線橫幅",
	"23" => "無線全頻",
	"24" => "無線插屏",
	"25" => "無線矩形",
	"26" => "無線視頻貼片",
	"27" => "無線動態創意"
);
$buyType = array(
	"無資料","固定價格採買","分成採買","包斷採買","CPD採買"
);
$chargeType = array(
	"無資料","分成","曝光","點擊"
);
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay' => '未填寫',
	'htmlOptions' => array('class'=>'table table-bordered'),
	'attributes'=>array(
		'tos_id',
		'name',
		array(
			'name' => '版位類型',
			'value' => $spaceType[$model->type],
		),	
		array(
			'name' => '版位廣告形式',
			'value' => $adFormat[$model->ad_format],
		),	
		'ratio_id',
		array(
			'name' => '默認廣告設定',
			'value' => (($model->def_creative_option) == 1) ? "系統默認廣告" : "自定默認廣告",
		),
		'def_creative_id',
		'material_format',
		array(
			'name' => '採買方式',
			'value' => Yii::app()->params['buyType'][$model->buy_type],
		),	
		array(
			'name' => '計費方式',
			'value' => Yii::app()->params['chrgeType'][$model->charge_type],
		),
		array(
			'name' => '價格 / 比例',
			'value' => ($model->price * Yii::app()->params['priceType'][$model->charge_type]),
		),					
		'width',
		'height',
		array(
			'name' => '資料建立時間',
			'value' => (empty($model->create_time)) ? "無資料" : date("Y-m-d H:i",$model->create_time),
		),
		array(
			'name' => 'TOS狀態',
			'value' => (($model->status) == -1) ? "停用" : "啟用",
		),
		'description'					
	),
)); ?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
</div>