<?php

/**
 * This is the model class for table "sellingprices".
 *
 * The followings are the available columns in table 'sellingprices':
 * @property string $id
 * @property string $idatetime
 * @property string $iditem
 * @property double $normalprice
 * @property double $minprice
 * @property string $approvalby
 * @property string $userlog
 * @property string $datetimelog
 */
class Sellingprices extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sellingprices';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, idatetime, iditem, approvalby, normalprice, minprice, userlog, datetimelog', 'required'),
			array('normalprice, minprice', 'numerical'),
			array('id, iditem, userlog', 'length', 'max'=>21),
			array('idatetime, datetimelog', 'length', 'max'=>19),
			array('approvalby', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idatetime, iditem, approvalby, normalprice, minprice, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'idatetime' => 'Tanggal',
			'iditem' => 'Nama Barang',
			'normalprice' => 'Harga Jual',
			'minprice' => 'Harga Jual Minimum',
			'approvalby'=> 'Disetujui oleh',
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
		$criteria->compare('idatetime',$this->idatetime,true);
		$criteria->compare('iditem',$this->iditem,true);
		$criteria->compare('normalprice',$this->normalprice);
		$criteria->compare('minprice',$this->minprice);
		$criteria->compare('approvalby',$this->approvalby);
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
	 * @return Sellingprices the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
