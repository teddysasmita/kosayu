<?php

/**
 * This is the model class for table "items".
 *
 * The followings are the available columns in table 'items':
 * @property string $id
 * @property string $code
 * @property integer $type
 * @property string $name
 * @property string $brand
 * @property string $objects
 * @property string $model
 * @property string $attribute
 * @property string $picture
 * @property string $userlog
 * @property string $datetimelog
 */
class Items extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'items';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, code, type, name, userlog, datetimelog', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('id, userlog', 'length', 'max'=>21),
			array('code', 'length', 'max'=>50),
			array('name, attribute', 'length', 'max'=>255),
			array('brand, objects, model', 'length', 'max'=>50),
			array('datetimelog', 'length', 'max'=>19),
			array('picture', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, type, name, brand, objects, model, attribute, picture, userlog, datetimelog', 'safe', 'on'=>'search'),
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
			'code' => 'Code',
			'type' => 'Jenis',
			'name' => 'Name',
			'brand' => 'Brand',
			'objects' => 'Objects',
			'model' => 'Model',
			'attribute' => 'Attribute',
			'picture' => 'Picture',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('brand',$this->brand,true);
		$criteria->compare('objects',$this->objects,true);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('attribute',$this->attribute,true);
		$criteria->compare('picture',$this->picture,true);
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
	 * @return Items the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
