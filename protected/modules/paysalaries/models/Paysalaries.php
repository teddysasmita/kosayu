<?php

/**
 * This is the model class for table "paysalaries".
 *
 * The followings are the available columns in table 'paysalaries':
 * @property string $id
 * @property string $regnum
 * @property string $idatetime
 * @property string $idemployee
 * @property integer $presence
 * @property double $overtime
 * @property double $late
 * @property double $lunch
 * @property double $payment
 * @property double $transport
 * @property double $receivable
 * @property double $bpjs
 * @property string $pmonth
 * @property string $pyear
 * @property double total
 * @property string $userlog
 * @property string $datetimelog
 */
class Paysalaries extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'paysalaries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, regnum, idatetime, idemployee, presence, overtime, transport, lunch, payment, receivable, bpjs, total, userlog, datetimelog', 'required'),
			array('presence', 'pmonth', 'pyear', 'numerical', 'integerOnly'=>true),
			array('overtime, late, lunch, payment, receivable, transport, total, bpjs', 'numerical'),
			array('id, idemployee, startdate, enddate, userlog', 'length', 'max'=>21),
			array('regnum', 'length', 'max'=>12),
			array('idatetime, datetimelog', 'length', 'max'=>19),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, regnum, idatetime, idemployee, presence, overtime, late, lunch, payment, receivable, bpjs, transport, total, pyear, pmonth, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'regnum' => 'Nomor Urut',
			'idatetime' => 'Tanggal',
			'idemployee' => 'Nama Karyawan',
			'presence' => 'Jumlah Kehadiran (Hari)',
			'overtime' => 'Lembur (menit)',
			'late' => 'Terlambat (menit)',
			'lunch' => 'Uang Makan',
			'receivable' => 'Piutang',
			'payment' => 'Bayar Piutang',
			'transport' => 'Uang Transport',
			'bpjs' => 'BPJS',
			'pmonth' => 'Bulan',
			'pyear' => 'Tahun',
			'total' => 'Total',
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
		$criteria->compare('regnum',$this->regnum,true);
		$criteria->compare('idatetime',$this->idatetime,true);
		$criteria->compare('idemployee',$this->idemployee,true);
		$criteria->compare('presence',$this->presence);
		$criteria->compare('overtime',$this->overtime);
		$criteria->compare('late',$this->late);
		$criteria->compare('lunch',$this->lunch);
		$criteria->compare('payment',$this->payment);
		$criteria->compare('bpjs',$this->bpjs);
		$criteria->compare('receivable',$this->receivable);
		$criteria->compare('transport',$this->transport);
		$criteria->compare('pmonth',$this->pmonth,true);
		$criteria->compare('pyear',$this->pyear,true);
		$criteria->compare('total',$this->total,true);
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
	 * @return Paysalaries the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
