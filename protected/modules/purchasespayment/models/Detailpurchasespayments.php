<?php

/**
 * This is the model class for table "detailpurchasespayments".
 *
 * The followings are the available columns in table 'detailpurchasespayments':
 * @property string $iddetail
 * @property string $id
 * @property string $idpurchase
 * @property double $discount
 * @property double $total
 * @property double $paid
 * @property double $amount
 * @property string $userlog
 * @property string $datetimelog
 */
class Detailpurchasespayments extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'detailpurchasespayments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iddetail, id, idpurchase, discount, total, paid, amount, userlog, datetimelog', 'required'),
			array('discount, total, paid, amount', 'numerical'),
			array('iddetail, id, idpurchase, userlog', 'length', 'max'=>21),
			array('datetimelog', 'length', 'max'=>19),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iddetail, id, idpurchase, discount, total, paid, amount, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'iddetail' => 'Iddetail',
			'id' => 'ID',
			'idpurchase' => 'Nomor Pembelian',
			'discount' => 'Diskon',
			'total' => 'Total',
			'paid' => 'Terbayar',
			'amount' => 'Dibayar',
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

		$criteria->compare('iddetail',$this->iddetail,true);
		$criteria->compare('id',$this->id,true);
		$criteria->compare('idpurchase',$this->idpurchase,true);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('total',$this->total);
		$criteria->compare('paid',$this->paid);
		$criteria->compare('amount',$this->amount);
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
	 * @return Detailpurchasespayments the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
