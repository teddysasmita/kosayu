<?php

class BarcodePrint1 extends CFormModel
{
	public $papersize;
	public $labelwidth;
	public $labellength;
	public $horizontal_padding;
	public $vertical_padding;
	
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
				// username and password are required
				array('papersize, labelwidth, labellength, horizontal_padding, vertical_padding', 'required'),
				// rememberMe needs to be a boolean
				array('labelwidth, labellength, horizontal_padding, vertical_padding', 'numerical'),
				// password needs to be authenticated
		);
	}
	
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
				'papersize'=>'Ukuran Kertas',
				'labelwidth'=>'Lebar Label',
				'labellength'=>'Panjang Label',
				'horizontal_padding'=>'Jarak Kolom',
				'vertical_padding'=>'Jarak Baris'
		);
	}
	
}


?>