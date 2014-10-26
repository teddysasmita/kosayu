<?php

/**
 * This is the model class for table "detailsalesinvoices".
 *
 * The followings are the available columns in table 'detailsalesinvoices':
 * @property string $iddetail
 * @property string $id
 * @property string $iditem
 * @property double $lqty
 * @property double $qty
 * @property double $discount
 * @property double $price
 * @property string $idorder
 * @property string $iddetailorder
 */
class Detailsalesinvoices extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'detailsalesinvoices';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iddetail, id, iditem, lqty, qty, idorder, iddetailorder', 'required'),
			array('lqty, qty, discount, price', 'numerical'),
			array('iddetail, id, iditem, idorder, iddetailorder', 'length', 'max'=>21),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iddetail, id, iditem, lqty, qty, discount, price, idorder, iddetailorder', 'safe', 'on'=>'search'),
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
			'iditem' => 'Jenis Barang',
			'lqty' => 'Sisa Qty',
			'qty' => 'Qty',
			'discount' => 'Discount',
			'price' => 'Harga',
			'idorder' => 'Idorder',
			'iddetailorder' => 'Iddetailorder',
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
		$criteria->compare('lqty',$this->lqty);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('price',$this->price);
		$criteria->compare('idorder',$this->idorder,true);
		$criteria->compare('iddetailorder',$this->iddetailorder,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Detailsalesinvoices the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
