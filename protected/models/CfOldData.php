<?php

/**
 * This is the model class for table "{{cfOldData}}".
 *
 * The followings are the available columns in table '{{cfOldData}}':
 * @property integer $id
 * @property string $zoneID
 * @property string $siteID
 * @property string $zoneName
 * @property string $companyID
 * @property string $countryCode
 * @property string $siteName
 * @property string $siteURL
 * @property string $comType
 * @property string $comName
 * @property string $comTaxID
 * @property string $comAddress
 * @property string $comMailAddr
 * @property string $comComment
 * @property string $comInvName
 * @property string $comTel
 * @property string $comFax
 * @property string $comEmail
 * @property string $finBankName
 * @property string $finBankID
 * @property string $finSubBankName
 * @property string $finSubBankID
 * @property string $finAccountName
 * @property string $finAccountNo
 * @property string $contactID
 * @property string $cwName
 * @property string $cwEmail
 * @property string $cwOPhone
 * @property string $cwMPhone
 * @property string $cwFax
 * @property string $cwComment
 */
class CfOldData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{cfOldData}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('zoneID, siteID, zoneName, companyID, countryCode, siteName, siteURL, comType, comName, comTaxID, comAddress, comMailAddr, comComment, comInvName, comTel, comFax, comEmail, finBankName, finBankID, finSubBankName, finSubBankID, finAccountName, finAccountNo, contactID, cwName, cwEmail, cwOPhone, cwMPhone, cwFax, cwComment', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, zoneID, siteID, zoneName, companyID, countryCode, siteName, siteURL, comType, comName, comTaxID, comAddress, comMailAddr, comComment, comInvName, comTel, comFax, comEmail, finBankName, finBankID, finSubBankName, finSubBankID, finAccountName, finAccountNo, contactID, cwName, cwEmail, cwOPhone, cwMPhone, cwFax, cwComment', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'zoneID' => 'Zone',
			'siteID' => 'Site',
			'zoneName' => 'Zone Name',
			'companyID' => 'Company',
			'countryCode' => 'Country Code',
			'siteName' => 'Site Name',
			'siteURL' => 'Site Url',
			'comType' => 'Com Type',
			'comName' => 'Com Name',
			'comTaxID' => 'Com Tax',
			'comAddress' => 'Com Address',
			'comMailAddr' => 'Com Mail Addr',
			'comComment' => 'Com Comment',
			'comInvName' => 'Com Inv Name',
			'comTel' => 'Com Tel',
			'comFax' => 'Com Fax',
			'comEmail' => 'Com Email',
			'finBankName' => 'Fin Bank Name',
			'finBankID' => 'Fin Bank',
			'finSubBankName' => 'Fin Sub Bank Name',
			'finSubBankID' => 'Fin Sub Bank',
			'finAccountName' => 'Fin Account Name',
			'finAccountNo' => 'Fin Account No',
			'contactID' => 'Contact',
			'cwName' => 'Cw Name',
			'cwEmail' => 'Cw Email',
			'cwOPhone' => 'Cw Ophone',
			'cwMPhone' => 'Cw Mphone',
			'cwFax' => 'Cw Fax',
			'cwComment' => 'Cw Comment',
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
		$criteria->compare('zoneID',$this->zoneID,true);
		$criteria->compare('siteID',$this->siteID,true);
		$criteria->compare('zoneName',$this->zoneName,true);
		$criteria->compare('companyID',$this->companyID,true);
		$criteria->compare('countryCode',$this->countryCode,true);
		$criteria->compare('siteName',$this->siteName,true);
		$criteria->compare('siteURL',$this->siteURL,true);
		$criteria->compare('comType',$this->comType,true);
		$criteria->compare('comName',$this->comName,true);
		$criteria->compare('comTaxID',$this->comTaxID,true);
		$criteria->compare('comAddress',$this->comAddress,true);
		$criteria->compare('comMailAddr',$this->comMailAddr,true);
		$criteria->compare('comComment',$this->comComment,true);
		$criteria->compare('comInvName',$this->comInvName,true);
		$criteria->compare('comTel',$this->comTel,true);
		$criteria->compare('comFax',$this->comFax,true);
		$criteria->compare('comEmail',$this->comEmail,true);
		$criteria->compare('finBankName',$this->finBankName,true);
		$criteria->compare('finBankID',$this->finBankID,true);
		$criteria->compare('finSubBankName',$this->finSubBankName,true);
		$criteria->compare('finSubBankID',$this->finSubBankID,true);
		$criteria->compare('finAccountName',$this->finAccountName,true);
		$criteria->compare('finAccountNo',$this->finAccountNo,true);
		$criteria->compare('contactID',$this->contactID,true);
		$criteria->compare('cwName',$this->cwName,true);
		$criteria->compare('cwEmail',$this->cwEmail,true);
		$criteria->compare('cwOPhone',$this->cwOPhone,true);
		$criteria->compare('cwMPhone',$this->cwMPhone,true);
		$criteria->compare('cwFax',$this->cwFax,true);
		$criteria->compare('cwComment',$this->cwComment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CfOldData the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
