<?php

/**
 * This is the model class for table "guides".
 *
 * The followings are the available columns in table 'guides':
 * @property string $id
 * @property string $firstname
 * @property string $lastname
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property string $idnum
 * @property string $idcomp
 * @property string $idpartner
 * @property string $userlog
 * @property string $datetimelog
 */
class Guides extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'guides';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, firstname, lastname, address, idnum, idpartner, idcomp, userlog, datetimelog', 'required'),
			array('id, idcomp, idpartner, userlog', 'length', 'max'=>21),
			array('idnum', 'length', 'max'=>50),
			array('commission', 'numerical', 'max'=>100),
			array('firstname, lastname, phone, email', 'length', 'max'=>100),
			array('address', 'length', 'max'=>255),
			array('datetimelog', 'length', 'max'=>19),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, firstname, lastname, address, phone, email, idnum, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'firstname' => 'Nama Awal',
			'lastname' => 'Nama Akhir',
			'address' => 'Alamat',
			'phone' => 'Telp',
			'email' => 'Email',
			'idnum' => 'No KTP',
			'idpartner' => 'Kelompok Rekanan',
			'idcomp' => 'Komposisi',
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
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('idnum',$this->idnum,true);
		$criteria->compare('idpartner',$this->idpartner,true);
		$criteria->compare('idcomp',$this->idcomp,true);
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
	 * @return Guides the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
s