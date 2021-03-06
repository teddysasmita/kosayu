<?php

/**
 * This is the model class for table "detailconsignpurchasesorders".
 *
 * The followings are the available columns in table 'detailconsignpurchasesorders':
 * @property string $iddetail
 * @property string $id
 * @property string $iditem
 * @property string $batchcode
 * @property string $idunit
 * @property double $qty
 * @property double $sellprice
 * @property double $buyprice
 * @property string $expirydate
 * @property string $userlog
 * @property string $datetimelog
 */
class Detailconsignpurchases extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'detailconsignpurchases';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iddetail, id, iditem, batchcode, qty, userlog, datetimelog', 'required'),
			array('qty, sellprice, buyprice', 'numerical'),
			array('iddetail, id, iditem, batchcode, idunit, userlog', 'length', 'max'=>21),
			array('expirydate, datetimelog', 'length', 'max'=>19),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iddetail, id, iditem, qty, expirydate, baseprice, buyprice, sellprice, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'iditem' => 'Nama Barang',
			'batchcode' => 'Nomor Batch',
			'qty' => 'Qty',
         	'idunit'=> 'Satuan',
			'sellprice' => 'Harga Jual',
			'buyprice' => 'Harga Beli',
			'expirydate' => 'Tgl Kdl',
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
		$criteria->compare('iditem',$this->iditem,true);
		//$criteria->compare('idunit',$this->idunit,true);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('sellprice',$this->sellprice);
		$criteria->compare('buyprice',$this->buyprice);
		$criteria->compare('expirydate',$this->expirydate,true);
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
	 * @return Detailconsignpurchasesorders the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
