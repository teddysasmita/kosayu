<?php

/**
 * This is the model class for table "salespostransfers".
 *
 * The followings are the available columns in table 'salespostransfers':
 * @property string $id
 * @property string $idbank
 * @property string $acctno
 * @property string $holdername
 * @property string $userlog
 * @property string $datetimelog
 */
class Salespostransfers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'salespostransfers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, idbank, acctno, holdername, userlog, datetimelog', 'required'),
			array('id, idbank, userlog', 'length', 'max'=>21),
			array('acctno', 'length', 'max'=>50),
			array('holdername', 'length', 'max'=>100),
			array('datetimelog', 'length', 'max'=>19),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idbank, acctno, holdername, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'idbank' => 'Idbank',
			'acctno' => 'Acctno',
			'holdername' => 'Holdername',
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
		$criteria->compare('idbank',$this->idbank,true);
		$criteria->compare('acctno',$this->acctno,true);
		$criteria->compare('holdername',$this->holdername,true);
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
	 * @return Salespostransfers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
