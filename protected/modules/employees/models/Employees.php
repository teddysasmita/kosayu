<?php

/**
 * This is the model class for table "employees".
 *
 * The followings are the available columns in table 'employees':
 * @property string $id
 * @property string $firstname
 * @property string $lastname
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property string $idnumber
 * @property string $idjobgroup
 * @property double wageamount
 * @property string $startdate
 * @property string $enddate
 * @property string $active
 * @property string $userlog
 * @property string $datetimelog
 */
class Employees extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'employees';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, firstname, lastname, address, idnumber, idjobgroup, wageamount, startdate, userlog, datetimelog', 'required'),
			array('wageamount', 'numerical'),
			array('id, idjobgroup, userlog', 'length', 'max'=>21),
			array('firstname, lastname, phone, idnumber', 'length', 'max'=>50),
			array('address, email', 'length', 'max'=>100),
			array('startdate, enddate, datetimelog', 'length', 'max'=>19),
			array('active', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, firstname, lastname, address, phone, email, idnumber, idjobgroup, wageamount, startdate, enddate, active, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'phone' => 'No Telp',
			'email' => 'Email',
			'idnumber' => 'Nomor Kartu ID',
			'idjobgroup' => 'Posisi',
			'wageamount' => 'Gaji Pokok',
			'startdate' => 'Tanggal Awal',
			'enddate' => 'Tanggal Akhir',
			'active' => 'Aktif',
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
		$criteria->compare('idnumber',$this->idnumber,true);
		$criteria->compare('idjobgroup',$this->idjobgroup,true);
		$criteria->compare('wageamount',$this->wageamount);
		$criteria->compare('startdate',$this->startdate,true);
		$criteria->compare('enddate',$this->enddate,true);
		$criteria->compare('active',$this->active,true);
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
	 * @return Employees the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
