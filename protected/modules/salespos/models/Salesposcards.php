<?php

/**
 * This is the model class for table "salesposcards".
 *
 * The followings are the available columns in table 'salesposcards':
 * @property string $id
 * @property string $name
 * @property string $kind
 * @property string $idbank
 * @property string $company
 * @property string $surchargeamount
 * @property string $surchargepct
 * @property string $userlog
 * @property string $datetimelog
 */
class Salesposcards extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'salesposcards';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, name, idbank, company, surchargeamount, surchargepct, kind, userlog, datetimelog', 'required'),
			array('id, idbank, userlog', 'length', 'max'=>21),
			array('surchargeamount, surchargepct', 'numerical'),
			array('name, company', 'length', 'max'=>100),
			array('datetimelog', 'length', 'max'=>19),
			array('kind', 'length', 'max'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, idbank, company, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'name' => 'Nama',
			'kind' => 'Jenis',		
			'idbank' => 'Bank Penerbit',
			'company' => 'Jaringan',
			'surchargeamount' => 'Biaya Admin',
			'surchargepct' => 'Biaya Admin (%)',
			'userlog' => 'Userlog',
			'datetimelog' => 'Datetimelog',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('kind',$this->kind,true);
		$criteria->compare('idbank',$this->idbank,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('userlog',$this->userlog,true);
		$criteria->compare('datetimelog',$this->datetimelog,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Salesposcards the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
