<?php

/**
 * This is the model class for table "{{mfOldData}}".
 *
 * The followings are the available columns in table '{{mfOldData}}':
 * @property string $zoneID
 * @property string $zoneName
 * @property string $siteID
 * @property string $countryCode
 * @property string $siteName
 * @property string $siteURL
 * @property string $comType
 * @property string $comContactID
 * @property string $comName
 * @property string $comTaxID
 * @property string $comAddress
 * @property string $comMailAddr
 * @property string $comComment
 * @property string $comInvName
 * @property string $comTel
 * @property string $comFax
 * @property string $comEmail
 * @property string $finChecksDays
 * @property string $finBankName
 * @property string $finBankID
 * @property string $finSubBankName
 * @property string $finSubBankID
 * @property string $finBranchBankID
 * @property string $finAccountName
 * @property string $finAccountNo
 * @property string $companyID
 * @property string $cwName
 * @property string $cwEmail
 * @property string $cwOPhone
 * @property string $cwMPhone
 * @property string $cwFax
 * @property integer $id
 */
class MfOldData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mfOldData}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('zoneID, zoneName, siteID, countryCode, siteName, siteURL, comType, comContactID, comName, comTaxID, comAddress, comMailAddr, comComment, comInvName, comTel, comFax, comEmail, finChecksDays, finBankName, finBankID, finSubBankName, finSubBankID, finBranchBankID, finAccountName, finAccountNo, companyID, cwName, cwEmail, cwOPhone, cwMPhone, cwFax', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('zoneID, zoneName, siteID, countryCode, siteName, siteURL, comType, comContactID, comName, comTaxID, comAddress, comMailAddr, comComment, comInvName, comTel, comFax, comEmail, finChecksDays, finBankName, finBankID, finSubBankName, finSubBankID, finBranchBankID, finAccountName, finAccountNo, companyID, cwName, cwEmail, cwOPhone, cwMPhone, cwFax, id', 'safe', 'on'=>'search'),
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
			'zoneID' => 'Zone',
			'zoneName' => 'Zone Name',
			'siteID' => 'Site',
			'countryCode' => 'Country Code',
			'siteName' => 'Site Name',
			'siteURL' => 'Site Url',
			'comType' => 'Com Type',
			'comContactID' => 'Com Contact',
			'comName' => 'Com Name',
			'comTaxID' => 'Com Tax',
			'comAddress' => 'Com Address',
			'comMailAddr' => 'Com Mail Addr',
			'comComment' => 'Com Comment',
			'comInvName' => 'Com Inv Name',
			'comTel' => 'Com Tel',
			'comFax' => 'Com Fax',
			'comEmail' => 'Com Email',
			'finChecksDays' => 'Fin Checks Days',
			'finBankName' => 'Fin Bank Name',
			'finBankID' => 'Fin Bank',
			'finSubBankName' => 'Fin Sub Bank Name',
			'finSubBankID' => 'Fin Sub Bank',
			'finBranchBankID' => 'Fin Branch Bank',
			'finAccountName' => 'Fin Account Name',
			'finAccountNo' => 'Fin Account No',
			'companyID' => 'Company',
			'cwName' => 'Cw Name',
			'cwEmail' => 'Cw Email',
			'cwOPhone' => 'Cw Ophone',
			'cwMPhone' => 'Cw Mphone',
			'cwFax' => 'Cw Fax',
			'id' => 'ID',
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

		$criteria->compare('zoneID',$this->zoneID,true);
		$criteria->compare('zoneName',$this->zoneName,true);
		$criteria->compare('siteID',$this->siteID,true);
		$criteria->compare('countryCode',$this->countryCode,true);
		$criteria->compare('siteName',$this->siteName,true);
		$criteria->compare('siteURL',$this->siteURL,true);
		$criteria->compare('comType',$this->comType,true);
		$criteria->compare('comContactID',$this->comContactID,true);
		$criteria->compare('comName',$this->comName,true);
		$criteria->compare('comTaxID',$this->comTaxID,true);
		$criteria->compare('comAddress',$this->comAddress,true);
		$criteria->compare('comMailAddr',$this->comMailAddr,true);
		$criteria->compare('comComment',$this->comComment,true);
		$criteria->compare('comInvName',$this->comInvName,true);
		$criteria->compare('comTel',$this->comTel,true);
		$criteria->compare('comFax',$this->comFax,true);
		$criteria->compare('comEmail',$this->comEmail,true);
		$criteria->compare('finChecksDays',$this->finChecksDays,true);
		$criteria->compare('finBankName',$this->finBankName,true);
		$criteria->compare('finBankID',$this->finBankID,true);
		$criteria->compare('finSubBankName',$this->finSubBankName,true);
		$criteria->compare('finSubBankID',$this->finSubBankID,true);
		$criteria->compare('finBranchBankID',$this->finBranchBankID,true);
		$criteria->compare('finAccountName',$this->finAccountName,true);
		$criteria->compare('finAccountNo',$this->finAccountNo,true);
		$criteria->compare('companyID',$this->companyID,true);
		$criteria->compare('cwName',$this->cwName,true);
		$criteria->compare('cwEmail',$this->cwEmail,true);
		$criteria->compare('cwOPhone',$this->cwOPhone,true);
		$criteria->compare('cwMPhone',$this->cwMPhone,true);
		$criteria->compare('cwFax',$this->cwFax,true);
		$criteria->compare('id',$this->id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MfOldData the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
