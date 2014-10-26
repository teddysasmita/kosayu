<?php

/**
 * This is the model class for table "detailpurchasesreturs".
 *
 * The followings are the available columns in table 'detailpurchasesreturs':
 * @property string $iddetail
 * @property string $id
 * @property string $iditem
 * @property double $acceptedqty
 * @property double $prevprice
 * @property double $price
 * @property double $discount
 * @property double $cost1
 * @property double $cost2
 * @property double $qty
 * @property string $userlog
 * @property string $datetimelog
 */
class Detailpurchasesreturs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'detailpurchasesreturs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iddetail, id, iditem, acceptedqty, prevprice, price, discount, qty, userlog, datetimelog', 'required'),
			array('acceptedqty, prevprice, price, discount, cost1, cost2, qty', 'numerical'),
			array('iddetail, id, iditem, userlog', 'length', 'max'=>21),
			array('datetimelog', 'length', 'max'=>19),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iddetail, id, iditem, acceptedqty, prevprice, price, discount, cost1, cost2, qty, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'acceptedqty' => 'Acceptedqty',
			'prevprice' => 'Prevprice',
			'price' => 'Price',
			'discount' => 'Discount',
			'cost1' => 'Cost1',
			'cost2' => 'Cost2',
			'qty' => 'Qty',
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
		$criteria->compare('acceptedqty',$this->acceptedqty);
		$criteria->compare('prevprice',$this->prevprice);
		$criteria->compare('price',$this->price);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('cost1',$this->cost1);
		$criteria->compare('cost2',$this->cost2);
		$criteria->compare('qty',$this->qty);
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
	 * @return Detailpurchasesreturs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
