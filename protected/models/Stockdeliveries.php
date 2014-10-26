<?php

/**
 * This is the model class for table "stockdeliveries".
 *
 * The followings are the available columns in table 'stockdeliveries':
 * @property string $id
 * @property string $regnum
 * @property string $idatetime
 * @property string $idcustomer
 * @property string $userlog
 * @property string $datetimelog
 */
class Stockdeliveries extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Stockdeliveries the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stockdeliveries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, regnum, idatetime, idcustomer, userlog, datetimelog', 'required'),
			array('id, idcustomer, userlog', 'length', 'max'=>21),
			array('regnum', 'length', 'max'=>12),
			array('idatetime, datetimelog', 'length', 'max'=>19),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, regnum, idatetime, idcustomer, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'regnum' => 'Regnum',
			'idatetime' => 'Idatetime',
			'idcustomer' => 'Idcustomer',
			'userlog' => 'Userlog',
			'datetimelog' => 'Datetimelog',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('regnum',$this->regnum,true);
		$criteria->compare('idatetime',$this->idatetime,true);
		$criteria->compare('idcustomer',$this->idcustomer,true);
		$criteria->compare('userlog',$this->userlog,true);
		$criteria->compare('datetimelog',$this->datetimelog,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}