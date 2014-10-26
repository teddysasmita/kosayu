<?php

/**
 * This is the model class for table "detailpurchasesinvoices".
 *
 * The followings are the available columns in table 'detailpurchasesinvoices':
 * @property string $iddetail
 * @property string $id
 * @property string $iditem
 * @property double $qty
 * @property double $discount
 * @property double $price
 * @property string $idorder
 * @property string $iddetailorder
 */
class Detailpurchasesinvoices extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Detailpurchasesinvoices the static model class
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
		return 'detailpurchasesinvoices';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iddetail, id, iditem, qty, idorder, iddetailorder', 'required'),
			array('qty, discount, price', 'numerical'),
			array('iddetail, id, iditem, idorder, iddetailorder', 'length', 'max'=>21),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('iddetail, id, iditem, qty, discount, price, idorder, iddetailorder', 'safe', 'on'=>'search'),
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
			'iditem' => 'Iditem',
			'qty' => 'Qty',
			'discount' => 'Discount',
			'price' => 'Price',
			'idorder' => 'Idorder',
			'iddetailorder' => 'Iddetailorder',
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

		$criteria->compare('iddetail',$this->iddetail,true);
		$criteria->compare('id',$this->id,true);
		$criteria->compare('iditem',$this->iditem,true);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('price',$this->price);
		$criteria->compare('idorder',$this->idorder,true);
		$criteria->compare('iddetailorder',$this->iddetailorder,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}