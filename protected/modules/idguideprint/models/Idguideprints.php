<?php

/**
 * This is the model class for table "idguideprints".
 *
 * The followings are the available columns in table 'idguideprints':
 * @property string $id
 * @property string $idatetime
 * @property string $regnum
 * @property string $papersize
 * @property double $paperwidth
 * @property double $paperheight
 * @property double $papersidem
 * @property double $paperbotm
 * @property double $labelwidth
 * @property double $labelheight
 * @property double $labelsidem
 * @property double $labelbotm
 * @property string $userlog
 * @property string $datetimelog
 */
class Idguideprints extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'idguideprints';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, idatetime, regnum, papersize, papersidem, paperbotm, labelwidth, labelheight, labelsidem, labelbotm, userlog, datetimelog', 'required'),
			array('paperwidth, paperheight, papersidem, paperbotm, labelwidth, labelheight, labelsidem, labelbotm', 'numerical'),
			array('id, userlog', 'length', 'max'=>21),
			array('idatetime, datetimelog', 'length', 'max'=>19),
			array('regnum', 'length', 'max'=>12),
			array('papersize', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idatetime, regnum, papersize, paperwidth, paperheight, papersidem, paperbotm, labelwidth, labelheight, labelsidem, labelbotm, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'idatetime' => 'Idatetime',
			'regnum' => 'Regnum',
			'papersize' => 'Ukuran Kertas',
			'paperwidth' => 'Lebar Kertas (mm)',
			'paperheight' => 'Tinggi Kertas (mm)',
			'papersidem' => 'Margin Samping Kertas (mm)',
			'paperbotm' => 'Margin Bawah Kertas (mm)',
			'labelwidth' => 'Lebar Kartu (mm)',
			'labelheight' => 'Tinggi Kartu (mm)',
			'labelsidem' => 'Margin Samping Kartu (mm)',
			'labelbotm' => 'Margin Bawah Kartu (mm)',
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
		$criteria->compare('regnum',$this->regnum,true);
		$criteria->compare('papersize',$this->papersize,true);
		$criteria->compare('paperwidth',$this->paperwidth);
		$criteria->compare('paperheight',$this->paperheight);
		$criteria->compare('papersidem',$this->papersidem);
		$criteria->compare('paperbotm',$this->paperbotm);
		$criteria->compare('labelwidth',$this->labelwidth);
		$criteria->compare('labelheight',$this->labelheight);
		$criteria->compare('labelsidem',$this->labelsidem);
		$criteria->compare('labelbotm',$this->labelbotm);
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
	 * @return Idguideprints the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
