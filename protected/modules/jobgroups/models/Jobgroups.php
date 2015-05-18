<?php

/**
 * This is the model class for table "jobgroups".
 *
 * The followings are the available columns in table 'jobgroups':
 * @property string $id
 * @property string $name
 * @property double $bonusamount
 * @property double $thrqty
 * @property double $cashieramount
 * @property string $wager
 * @property string $bonus
 * @property string $thr
 * @property string $cashier
 * @property string $userlog
 * @property string $datetimelog
 */
class Jobgroups extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'jobgroups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, name, bonus, thr, cashier, wager, userlog, datetimelog', 'required'),
			array('cashieramount, thrqty, bonusamount', 'numerical'),
			array('id, userlog', 'length', 'max'=>21),
			array('name', 'length', 'max'=>100),
			array('wager, bonus, thr, cashier', 'length', 'max'=>1),
			array('datetimelog', 'length', 'max'=>19),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, cashieramount, thrqty, bonusamount, bonus, thr, cashier, wager, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'bonusamount' => 'Bonus',
			'cashieramount' => 'Tunjangan Kasir',
			'thrqty' => 'THR',
			'bonus' => 'Bonus',
			'thr' => 'Thr',
			'wager' => 'Gaji Pokok',
 			'cashier' => 'Tunjangan Kasir',
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
		$criteria->compare('bonusamount',$this->bonusamount,true);
		$criteria->compare('cashieramount',$this->cashieramount,true);
		$criteria->compare('thrqty',$this->thrqty,true);
		$criteria->compare('wager',$this->wager,true);
		$criteria->compare('bonus',$this->bonus,true);
		$criteria->compare('thr',$this->thr,true);
		$criteria->compare('wager',$this->wager,true);
		$criteria->compare('cashier',$this->cashier,true);
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
	 * @return Jobgroups the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
