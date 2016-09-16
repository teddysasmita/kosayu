<?php

/**
 * This is the model class for table "detailguidepayments".
 *
 * The followings are the available columns in table 'detailguidepayments':
 * @property string $iddetail
 * @property string $id
 * @property string $stickernum
 * @property string $stickerdate
 * @property string $regnum
 * @property string $iditem
 * @property double $qty
 * @property double $price
 * @property double $discount
 * @property string $idcashier
 * @property double $pct
 * @property double $amount
 * @property string $userlog
 * @property string $datetimelog
 */
class Detailguidepayments extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'detailguidepayments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iddetail, id, stickernum, stickerdate, regnum, iditem, qty, price, discount, idcashier, pct, amount, userlog, datetimelog', 'required'),
			array('qty, price, discount, pct, amount', 'numerical'),
			array('iddetail, id, iditem, idcashier, userlog', 'length', 'max'=>21),
			array('stickernum', 'length', 'max'=>10),
			array('stickerdate, datetimelog', 'length', 'max'=>19),
			array('regnum', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iddetail, id, stickernum, stickerdate, regnum, iditem, qty, price, discount, idcashier, pct, amount, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'stickernum' => 'Stickernum',
			'stickerdate' => 'Stickerdate',
			'regnum' => 'Regnum',
			'iditem' => 'Iditem',
			'qty' => 'Qty',
			'price' => 'Price',
			'discount' => 'Discount',
			'idcashier' => 'Idcashier',
			'pct' => 'Pct',
			'amount' => 'Amount',
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
		$criteria->compare('stickernum',$this->stickernum,true);
		$criteria->compare('stickerdate',$this->stickerdate,true);
		$criteria->compare('regnum',$this->regnum,true);
		$criteria->compare('iditem',$this->iditem,true);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('price',$this->price);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('idcashier',$this->idcashier,true);
		$criteria->compare('pct',$this->pct);
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
	 * @return Detailguidepayments the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}