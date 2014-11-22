<?php

/**
 * This is the model class for table "detailtippayments".
 *
 * The followings are the available columns in table 'detailtippayments':
 * @property string $iddetail
 * @property string $id
 * @property string $invoicenum
 * @property string $idatetime
 * @property double $amount
 * @property double $totaldiscount
 * @property string idcashier
 * @property string cashierlog
 */
class Detailtippayments extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'detailtippayments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iddetail, id, invoicenum, idatetime, amount, totaldiscount, idcashier, cashierlog', 'required'),
			array('amount, totaldiscount', 'numerical'),
			array('iddetail, id, idcashier', 'length', 'max'=>21),
			array('invoicenum', 'length', 'max'=>12),
			array('idatetime, cashierlog', 'length', 'max'=>19),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iddetail, id, invoicenum, idatetime, amount, totaldiscount', 'safe', 'on'=>'search'),
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
			'invoicenum' => 'Nomor Nota',
			'idatetime' => 'Tanggal',
			'amount' => 'Jumlah',
			'totaldiscount' => 'Potongan',
			'cashierlog'=>'Waktu',
			'idcashier'=>'Kasir'
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
		$criteria->compare('invoicenum',$this->invoicenum,true);
		$criteria->compare('idatetime',$this->idatetime,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('totaldiscount',$this->totaldiscount);
		$criteria->compare('idcashier',$this->idcashier);
		$criteria->compare('cashierlog',$this->cashierlog);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Detailtippayments the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
