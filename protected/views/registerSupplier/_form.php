<div class="form">
<style>
	.errorMessage{
		color: red;
	}
</style>
<script type="text/javascript">
$(function() {
	var taiwanBank = [{ id : "004", label : "台灣銀行"},{ id : "005", label : "土地銀行"},{ id : "006", label : "合庫商銀"},{ id : "007", label : "第一銀行"},{ id : "008", label : "華南銀行"},{ id : "009", label : "彰化銀行"},{ id : "011", label : "上海銀行"},{ id : "012", label : "台北富邦"},{ id : "013", label : "國泰世華"},{ id : "016", label : "高雄銀行"},{ id : "017", label : "兆豐商銀"},{ id : "018", label : "農業金庫"},{ id : "021", label : "花旗銀行"},{ id : "022", label : "美國銀行"},{ id : "025", label : "首都銀行"},{ id : "039", label : "澳盛(台灣)銀行"},{ id : "040", label : "中華開發"},{ id : "050", label : "臺灣企銀"},{ id : "052", label : "渣打國際商銀"},{ id : "053", label : "台中商銀"},{ id : "054", label : "京城商銀"},{ id : "072", label : "德意志銀行"},{ id : "075", label : "東亞銀行"},{ id : "081", label : "匯豐(台灣)商業銀行"},{ id : "101", label : "瑞興銀行"},{ id : "102", label : "華泰銀行"},{ id : "103", label : "臺灣新光商銀"},{ id : "104", label : "台北五信"},{ id : "108", label : "陽信銀行"},{ id : "114", label : "基隆一信"},{ id : "115", label : "基隆二信"},{ id : "118", label : "板信銀行"},{ id : "119", label : "淡水一信"},{ id : "120", label : "淡水信合社"},{ id : "124", label : "宜蘭信合社"},{ id : "127", label : "南資中心-桃信"},{ id : "130", label : "新竹一信"},{ id : "132", label : "新竹三信"},{ id : "139", label : "竹南信合社"},{ id : "146", label : "台中二信"},{ id : "147", label : "三信銀行"},{ id : "158", label : "彰化一信"},{ id : "161", label : "彰化五信"},{ id : "162", label : "彰化六信"},{ id : "163", label : "彰化十信"},{ id : "165", label : "鹿港信合社"},{ id : "178", label : "嘉義三信"},{ id : "188", label : "台南三信"},{ id : "204", label : "高雄三信"},{ id : "215", label : "花蓮一信"},{ id : "216", label : "花蓮二信"},{ id : "222", label : "澎湖一信"},{ id : "223", label : "澎湖二信"},{ id : "224", label : "金門信合社"},{ id : "512", label : "雲林漁會"},{ id : "515", label : "嘉義漁會"},{ id : "517", label : "南市區漁會"},{ id : "518", label : "南縣漁會"},{ id : "520", label : "南區資訊ˋ中心"},{ id : "520", label : "南區資訊中心"},{ id : "521", label : "南農中心"},{ id : "523", label : "南農中心"},{ id : "524", label : "新港漁會"},{ id : "525", label : "澎湖區漁會"},{ id : "605", label : "高雄市高雄地區農會"},{ id : "612", label : "南農中心"},{ id : "613", label : "名間農會"},{ id : "614", label : "南農中心"},{ id : "616", label : "南農中心"},{ id : "617", label : "南農中心"},{ id : "618", label : "南農中心"},{ id : "619", label : "南農中心"},{ id : "620", label : "南農中心"},{ id : "621", label : "南農中心"},{ id : "622", label : "南農中心"},{ id : "624", label : "澎湖農會"},{ id : "625", label : "臺中市臺中地區農會"},{ id : "627", label : "連江縣農會"},{ id : "700", label : "中華郵政"},{ id : "803", label : "聯邦銀行"},{ id : "805", label : "遠東銀行"},{ id : "806", label : "元大銀行"},{ id : "807", label : "永豐銀行"},{ id : "808", label : "玉山銀行"},{ id : "809", label : "凱基銀行"},{ id : "810", label : "星展銀行-原寶華銀行"},{ id : "812", label : "台新銀行"},{ id : "814", label : "大眾銀行"},{ id : "815", label : "日盛銀行"},{ id : "816", label : "安泰銀行"},{ id : "822", label : "中國信託"},{ id : "901", label : "大里市農會"},{ id : "903", label : "汐止農會"},{ id : "904", label : "新莊農會"},{ id : "910", label : "聯資中心"},{ id : "912", label : "冬山農會"},{ id : "916", label : "草屯農會"},{ id : "922", label : "臺南市臺南地區農會"},{ id : "928", label : "板橋農會"},{ id : "951", label : "北農中心"},{ id : "954", label : "中農中心"}];	

	$( "#SupplierRegister_bank_name" ).autocomplete({
		minLength: 0,
		source: taiwanBank,
		focus: function( event, ui ) {
			$( "#SupplierRegister_bank_name" ).val( ui.item.label );
			return false;
		},
		select: function( event, ui ) {
			$( "#SupplierRegister_bank_id" ).val( ui.item.id );
			return false;
		}
	})
	.autocomplete( "instance" )._renderItem = function( ul, item ) {
		return $("<li>").append(item.label).appendTo(ul);
	};
});
</script>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'site-setting-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

	<?php 
	$err = $form->errorSummary($model);
	if(!empty($err)):?>
	<div class="alert alert-danger" role="alert">
		<span class="sr-only">錯誤:</span>
		<?php echo $err; ?>
	</div>	
	<?php endif;?>

	<div class="panel panel-default">
		<div class="panel-heading">基本資料</div>
		<div class="panel-body">

			<div class="form-group">

				<label><?php echo $form->labelEx($model,'type'); ?></label>
				<div><?php echo $form->dropDownList($model,'type',Yii::app()->params['supplierType']); ?></div>
				<p class="text-danger"><?php echo $form->error($model,'type'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'name'); ?></label>
				<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"名稱")); ?>
				<p class="text-danger"><?php echo $form->error($model,'name'); ?></p>
			</div>	

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'company_name'); ?></label>
				<?php echo $form->textField($model,'company_name',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"公司名稱 / 個人名稱")); ?>
				<p class="text-danger"><?php echo $form->error($model,'company_name'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'invoice_name'); ?></label>
				<?php echo $form->textField($model,'invoice_name',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"發票抬頭")); ?>
				<p class="text-danger"><?php echo $form->error($model,'invoice_name'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'tax_id'); ?></label>
				<?php echo $form->textField($model,'tax_id',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"統一編號 / 身分證字號")); ?>
				<p class="text-danger"><?php echo $form->error($model,'tax_id'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'country_code'); ?></label>
				<div><?php echo $form->dropDownList($model,'country_code',Yii::app()->params['countryCode']); ?></div>
				<p class="text-danger"><?php echo $form->error($model,'country_code'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'company_address'); ?></label>
				<?php echo $form->textField($model,'company_address',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"公司地址 / 居住地址")); ?>
				<p class="text-danger"><?php echo $form->error($model,'company_address'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'mail_address'); ?></label>
				<div><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>我們將依此地址做為單據收發之用途</div>
				<?php echo $form->textField($model,'mail_address',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"郵件地址")); ?>
				<p class="text-danger"><?php echo $form->error($model,'mail_address'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'email'); ?></label>
				<div><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>我們將依此地址做為您日後登入的帳號，並且透過此地址寄發您的密碼</div>
				<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"電子郵件")); ?>
				<p class="text-danger"><?php echo $form->error($model,'email'); ?></p>
			</div>	

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'tel'); ?></label>
				<?php echo $form->textField($model,'tel',array('size'=>60,'maxlength'=>50 , "class"=>"form-control" , "placeholder"=>"電話")); ?>
				<p class="text-danger"><?php echo $form->error($model,'tel'); ?></p>
			</div>	

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'fax'); ?></label>
				<?php echo $form->textField($model,'fax',array('size'=>60,'maxlength'=>50 , "class"=>"form-control" , "placeholder"=>"傳真")); ?>
				<p class="text-danger"><?php echo $form->error($model,'fax'); ?></p>
			</div>	

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'certificate_image'); ?></label>
				<div><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>請上傳您做為銀行帳戶的存摺影本，JPG格式4MB以內</div>				
				<div>
					<?php 
					if(isset($model->certificate_image)){
							if (strpos($model->certificate_image,"http") === false) {
								$img = Yii::app()->params['baseUrl'] . "/upload/registerSupplier/" . $model->certificate_image;
							} 

							if($model->isNewRecord){
								echo '<input type="hidden" name="SupplierRegister[certificate_image]" value="'.$model->certificate_image.'">';
							}
					?>
						<img src="<?php echo $img;?>" alt="certificate_image" class="img-thumbnail">
					<?php }?>		
				</div>		
				<input class="image-upload" name="certificate_image" type="file">
				<p class="text-danger"><?php echo $form->error($model,'certificate_image'); ?></p>	
			</div>			

		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">主要連絡人</div>
		<div class="panel-body">

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'contacts'); ?></label>
				<?php echo $form->textField($model,'contacts',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"主要聯絡人")); ?>
				<p class="text-danger"><?php echo $form->error($model,'contacts'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'contacts_email'); ?></label>
				<?php echo $form->textField($model,'contacts_email',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"電子郵件")); ?>
				<p class="text-danger"><?php echo $form->error($model,'contacts_email'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'contacts_tel'); ?></label>
				<?php echo $form->textField($model,'contacts_tel',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"電話")); ?>
				<p class="text-danger"><?php echo $form->error($model,'contacts_tel'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'contacts_moblie'); ?></label>
				<?php echo $form->textField($model,'contacts_moblie',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"手機")); ?>
				<p class="text-danger"><?php echo $form->error($model,'contacts_moblie'); ?></p>
			</div>			

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'contacts_fax'); ?></label>
				<?php echo $form->textField($model,'contacts_fax',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"傳真")); ?>
				<p class="text-danger"><?php echo $form->error($model,'contacts_fax'); ?></p>
			</div>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">帳務</div>
		<div class="panel-body">
			<div class="form-group">
				<label><?php echo $form->labelEx($model,'bank_type'); ?></label>
				<div><?php echo $form->dropDownList($model,'bank_type',Yii::app()->params['bankType']); ?></div>
				<p class="text-danger"><?php echo $form->error($model,'bank_type'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'bank_name'); ?></label>
				<?php echo $form->textField($model,'bank_name',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"銀行名稱")); ?>
				<p class="text-danger"><?php echo $form->error($model,'bank_name'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'bank_id'); ?></label>
				<?php echo $form->textField($model,'bank_id',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"銀行編號")); ?>
				<p class="text-danger"><?php echo $form->error($model,'bank_id'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'bank_sub_name'); ?></label>
				<?php echo $form->textField($model,'bank_sub_name',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"分行名稱")); ?>
				<p class="text-danger"><?php echo $form->error($model,'bank_sub_name'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'bank_sub_id'); ?></label>
				<?php echo $form->textField($model,'bank_sub_id',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"分行編號")); ?>
				<p class="text-danger"><?php echo $form->error($model,'bank_sub_id'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'account_name'); ?></label>
				<?php echo $form->textField($model,'account_name',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"銀行帳戶名稱")); ?>
				<p class="text-danger"><?php echo $form->error($model,'account_name'); ?></p>
			</div>

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'account_number'); ?></label>
				<?php echo $form->textField($model,'account_number',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"銀行帳號")); ?>
				<p class="text-danger"><?php echo $form->error($model,'account_number'); ?></p>
			</div>			
					
			<div class="form-group">
				<label><?php echo $form->labelEx($model,'bank_swift'); ?></label>
				<div><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>若您以國外帳戶收款，請向您的銀行詢問Swift代號</div>
				<?php echo $form->textField($model,'bank_swift',array('size'=>60,'maxlength'=>255 , "class"=>"form-control" , "placeholder"=>"Swift代號")); ?>
				<p class="text-danger"><?php echo $form->error($model,'bank_swift'); ?></p>
			</div>	

			<div class="form-group">
				<label><?php echo $form->labelEx($model,'bank_book_img'); ?></label>
				<div><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>請上傳您做為銀行帳戶的存摺影本，JPG格式4MB以內</div>				
				<div>
					<?php 
					if(isset($model->bank_book_img)){
							if (strpos($model->bank_book_img,"http") === false) {
								$img = Yii::app()->params['baseUrl'] . "/upload/registerSupplier/" . $model->bank_book_img;
							} 

							if($model->isNewRecord){
								echo '<input type="hidden" name="SupplierRegister[bank_book_img]" value="'.$model->bank_book_img.'">';
							}
					?>
						<img src="<?php echo $img;?>" alt="bank_book_img" class="img-thumbnail">
					<?php }?>		
				</div>		
				<input class="image-upload" name="bank_book_img" type="file">
				<p class="text-danger"><?php echo $form->error($model,'bank_book_img'); ?></p>	
			</div>


		</div>
	</div>	

	<div class="form-group buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '儲存',array('class' => 'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->