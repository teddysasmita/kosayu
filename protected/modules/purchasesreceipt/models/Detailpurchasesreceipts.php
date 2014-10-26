<?php

/**
 * This is the model class for table "detailpurchasesreceipts".
 *
 * The followings are the available columns in table 'detailpurchasesreceipts':
 * @property string $iddetail
 * @property string $id
 * @property string $iditem
 * @property string $idunit
 * @property string $idwarehouse
 * @property string $idpurchaseorder
 * @property double $qty
 * @property double $leftqty
 * @property string $userlog
 * @property string $datetimelog
 */
class Detailpurchasesreceipts extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'detailpurchasesreceipts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iddetail, id, iditem, idwarehouse, idpurchaseorder, leftqty, userlog, datetimelog', 'required'),
			array('qty, leftqty', 'numerical'),
			array('iddetail, id, iditem, idunit, idwarehouse, idpurchaseorder, userlog', 'length', 'max'=>21),
			array('datetimelog', 'length', 'max'=>19),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iddetail, id, iditem, idunit, idwarehouse, idpurchaseorder, qty, leftqty, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'idunit' => 'Satuan',
			'idwarehouse' => 'Gudang',
			'idpurchaseorder' => 'Nomor PO',
			'qty' => 'Qty',
			'leftqty' => 'Leftqty',
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
		$criteria->compare('idunit',$this->idunit,true);
		$criteria->compare('idwarehouse',$this->idwarehouse,true);
		$criteria->compare('idpurchaseorder',$this->idpurchaseorder,true);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('leftqty',$this->leftqty);
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
	 * @return Detailpurchasesreceipts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
