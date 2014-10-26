<?php

/**
 * This is the model class for table "salesreplace".
 *
 * The followings are the available columns in table 'salesreplace':
 * @property string $id
 * @property string $idatetime
 * @property string $regnum
 * @property string $invnum
 * @property double $total
 * @property double $discount
 * @property string $reason
 * @property string $userlog
 * @property string $datetimelog
 */
class Salesreplace extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'salesreplace';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, idatetime, regnum, invnum, total, discount, reason, userlog, datetimelog', 'required'),
			array('total, discount', 'numerical'),
			array('id, userlog', 'length', 'max'=>21),
			array('idatetime, datetimelog', 'length', 'max'=>19),
			array('regnum, invnum', 'length', 'max'=>12),
			array('reason', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idatetime, regnum, invnum, total, discount, reason, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'idatetime' => 'Idatetime',
			'regnum' => 'Regnum',
			'invnum' => 'Invnum',
			'total' => 'Total',
			'discount' => 'Discount',
			'reason' => 'Reason',
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
		$criteria->compare('idatetime',$this->idatetime,true);
		$criteria->compare('regnum',$this->regnum,true);
		$criteria->compare('invnum',$this->invnum,true);
		$criteria->compare('total',$this->total);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('reason',$this->reason,true);
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
	 * @return Salesreplace the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
