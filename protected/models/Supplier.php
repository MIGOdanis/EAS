<?php

/**
 * This is the model class for table "{{supplier}}".
 *
 * The followings are the available columns in table '{{supplier}}':
 * @property integer $id
 * @property string $tos_id
 * @property string $name
 * @property string $contacts
 * @property string $contacts_email
 * @property string $contacts_tel
 * @property string $contacts_moblie
 * @property string $contacts_fax
 * @property string $tel
 * @property string $fax
 * @property string $email
 * @property string $mobile
 * @property string $company_name
 * @property string $company_address
 * @property string $mail_address
 * @property string $invoice_name
 * @property string $tax_id
 * @property integer $type
 * @property string $country_code
 * @property string $account_name
 * @property string $account_number
 * @property string $bank_name
 * @property string $bank_id
 * @property string $bank_sub_name
 * @property string $bank_sub_id
 * @property integer $bank_type
 * @property string $bank_swift
 * @property string $remark
 * @property integer $create_time
 * @property integer $sync_time
 * @property integer $status
 */
class Supplier extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{supplier}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tos_id, create_time, sync_time, status', 'required'),
			array('type, bank_type, create_time, sync_time, status', 'numerical', 'integerOnly'=>true),
			array('tos_id', 'length', 'max'=>20),
			array('name, contacts, company_address, mail_address, account_name, account_number, bank_name, bank_sub_name', 'length', 'max'=>255),
			array('contacts_email, contacts_tel, contacts_moblie, contacts_fax, tel, fax, email, mobile, company_name, invoice_name, tax_id, bank_id, bank_sub_id', 'length', 'max'=>50),
			array('country_code', 'length', 'max'=>10),
			array('bank_swift', 'length', 'max'=>15),
			array('remark, certificate_image, bank_book_img, bank_swift2', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tos_id, name, contacts, contacts_email, contacts_tel, contacts_moblie, contacts_fax, tel, fax, email, mobile, company_name, company_address, mail_address, invoice_name, tax_id, type, country_code, account_name, account_number, bank_name, bank_id, bank_sub_name, bank_sub_id, bank_type, bank_swift, remark, create_time, sync_time, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'site' => array(self::HAS_MANY, 'Site', array('supplier_id' => 'tos_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tos_id' => 'TOS-ID',
			'name' => '供應商名稱',
			'contacts' => '聯絡人',
			'contacts_email' => '聯絡人Email',
			'contacts_tel' => '聯絡人電話',
			'contacts_moblie' => '聯絡人手機',
			'contacts_fax' => '聯絡人傳真',
			'fax' => '公司傳真',
			'tel' => '公司電話',
			'email' => '公司電子郵件',
			'mobile' => '公司行動電話',
			'company_name' => '公司名稱',
			'company_address' => '公司地址',
			'mail_address' => '郵件地址',
			'invoice_name' => '發票抬頭',
			'tax_id' => '統一編號 / 身分字號',
			'type' => 'Type',
			'country_code' => '國家代碼',
			'account_name' => '銀行戶名',
			'account_number' => '銀行戶號',
			'bank_name' => '銀行名稱',
			'bank_id' => '銀行代號',
			'bank_sub_name' => '分行名稱',
			'bank_sub_id' => '分行代號',
			'bank_type' => '銀行類型',
			'bank_swift' => '國際銀行代號(Swift)',
			'bank_swift2' => '中繼銀行代號(Swift 港澳帳戶請填寫)',
			'remark' => '備註',
			'create_time' => '建立時間',
			'sync_time' => '同步時間',
			'status' => '狀態(TOS)',
			'certificate_image' => '營利登記證 / 身份證 / 護照',
			'bank_book_img' => '存摺影本'			
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('tos_id',$this->tos_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('contacts',$this->contacts,true);
		$criteria->compare('contacts_email',$this->contacts_email,true);
		$criteria->compare('contacts_tel',$this->contacts_tel,true);
		$criteria->compare('contacts_moblie',$this->contacts_moblie,true);
		$criteria->compare('contacts_fax',$this->contacts_fax,true);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('fax',$this->fax,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('company_address',$this->company_address,true);
		$criteria->compare('mail_address',$this->mail_address,true);
		$criteria->compare('invoice_name',$this->invoice_name,true);
		$criteria->compare('tax_id',$this->tax_id,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('country_code',$this->country_code,true);
		$criteria->compare('account_name',$this->account_name,true);
		$criteria->compare('account_number',$this->account_number,true);
		$criteria->compare('bank_name',$this->bank_name,true);
		$criteria->compare('bank_id',$this->bank_id,true);
		$criteria->compare('bank_sub_name',$this->bank_sub_name,true);
		$criteria->compare('bank_sub_id',$this->bank_sub_id,true);
		$criteria->compare('bank_type',$this->bank_type);
		$criteria->compare('bank_swift',$this->bank_swift,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('sync_time',$this->sync_time);
		$criteria->compare('status',$this->status);

		if(isset($_GET['type']) && !empty($_GET['type']))
			$criteria->addCondition("t.type = " . ($_GET['type'] - 1));

		if(isset($_GET['supplier_id']) && $_GET['supplier_id'] > 0)
			$criteria->addCondition("tos_id = " . (int)$_GET['supplier_id']);

		return new CActiveDataProvider($this, array(
			'pagination' => array(
				'pageSize' => 50
			),
			'sort' => array(
				'defaultOrder' => 't.id DESC',
			),
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Supplier the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
