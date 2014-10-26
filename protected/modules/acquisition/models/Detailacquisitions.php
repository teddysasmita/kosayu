<?php

/**
 * This is the model class for table "detailacquisitions".
 *
 * The followings are the available columns in table 'detailacquisitions':
 * @property string $id
 * @property string $iddetail
 * @property string $serialnum
 * @property string $avail
 * @property string $userlog
 * @property string $datetimelog
 */
class Detailacquisitions extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'detailacquisitions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, iddetail, serialnum, avail, userlog, datetimelog', 'required'),
			array('id, iddetail, userlog', 'length', 'max'=>21),
			array('serialnum', 'length', 'max'=>100),
			array('avail', 'length', 'max'=>1),
			array('datetimelog', 'length', 'max'=>19),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, iddetail, serialnum, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'iddetail' => 'Iddetail',
			'serialnum' => 'Serialnum',
			'avail' => 'Kondisi',
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
		$criteria->compare('iddetail',$this->iddetail,true);
		$criteria->compare('serialnum',$this->serialnum,true);
		$criteria->compare('avail',$this->avail,true);
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
	 * @return Detailacquisitions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
