<?php

/**
 * This is the model class for table "detailpurchasesorders".
 *
 * The followings are the available columns in table 'detailpurchasesorders':
 * @property string $iddetail
 * @property string $id
 * @property string $iditem
 * @property string $idunit
 * @property double $qty
 * @property double $discount
 * @property double $price
 * @property double $cost1
 * @property double $cost2
 * @property string $userlog
 * @property string $datetimelog
 */
class Detailpurchasesorders extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'detailpurchasesorders';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iddetail, id, iditem, qty, userlog, datetimelog', 'required'),
			array('qty, discount, price, cost1, cost2', 'numerical'),
			array('iddetail, id, iditem, idunit, userlog', 'length', 'max'=>21),
			array('datetimelog', 'length', 'max'=>19),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iddetail, id, iditem, qty, discount, price, cost1, cost2, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'qty' => 'Qty',
         'idunit'=> 'Satuan',
         'discount' => 'Discount',
			'price' => 'Price',
			'cost1' => 'Biaya 1',
			'cost2' => 'Biaya 2',
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
		$criteria->compare('discount',$this->discount);
		$criteria->compare('price',$this->price);
		$criteria->compare('cost1',$this->cost1);
		$criteria->compare('cost2',$this->cost2);
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
	 * @return Detailpurchasesorders the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
