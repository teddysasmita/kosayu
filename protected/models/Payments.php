<?php

/**
 * This is the model class for table "payments".
 *
 * The followings are the available columns in table 'payments':
 * @property string $id
 * @property string $idtransaction
 * @property string $idatetime
 * @property string $c_idcurr
 * @property string $c_idrate
 * @property double $amount
 * @property string $method
 * @property string $bg_idbank
 * @property string $bg_num
 * @property string $bg_pubdate
 * @property string $bg_duedate
 * @property string $bg_receiver
 * @property string $bg_type
 * @property string $bg_status
 * @property string $tr_idbank
 * @property string $tr_receiver
 * @property string $tr_bank
 * @property string $userlog
 * @property string $datetimelog
 */
class Payments extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'payments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, idtransaction, idatetime, amount, method, userlog, datetimelog', 'required'),
			array('amount', 'numerical'),
			array('id, idtransaction, bg_idbank, bg_receiver, tr_idbank, userlog', 'length', 'max'=>21),
			array('idatetime, bg_pubdate, bg_duedate, datetimelog', 'length', 'max'=>19),
			array('method', 'length', 'max'=>3),
			array('bg_type, bg_status', 'length', 'max'=>1),
			array('tr_receiver', 'length', 'max'=>100),
			array('tr_bank, bg_num', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idtransaction, idatetime, c_idcurr, c_idrate, amount, method, bg_idbank, bg_pubdate, bg_duedate, bg_receiver, bg_type, bg_status, tr_idbank, tr_receiver, tr_bank, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'idtransaction' => 'No Transaksi',
			'idatetime' => 'Tanggal',
			'amount' => 'Jumlah',
			'method' => 'Metode',
			'bg_idbank' => 'Bank Penerbit',
			'bg_num' => 'Nomor BG/Cheque',
			'bg_pubdate' => 'Tanggal Tulis',
			'bg_duedate' => 'Tanggal Jatuh Tempo',
			'bg_receiver' => 'Penerima',
			'bg_type' => 'Jenis',
			'bg_status' => 'status',
			'tr_idbank' => 'Rekening Bank',
			'tr_receiver' => 'Penerima',
			'tr_bank' => 'Rekening penerima',
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
		$criteria->compare('idtransaction',$this->idtransaction,true);
		$criteria->compare('idatetime',$this->idatetime,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('method',$this->method,true);
		$criteria->compare('bg_idbank',$this->bg_idbank,true);
		$criteria->compare('bg_num',$this->bg_num,true);
		$criteria->compare('bg_pubdate',$this->bg_pubdate,true);
		$criteria->compare('bg_duedate',$this->bg_duedate,true);
		$criteria->compare('bg_receiver',$this->bg_receiver,true);
		$criteria->compare('bg_type',$this->bg_type,true);
		$criteria->compare('bg_status',$this->bg_status,true);
		$criteria->compare('tr_idbank',$this->tr_idbank,true);
		$criteria->compare('tr_receiver',$this->tr_receiver,true);
		$criteria->compare('tr_bank',$this->tr_bank,true);
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
	 * @return Payments the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
