<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
		'Proses'=>array('/site/proses'),
		'Daftar',
);

$this->menu=array(
		//array('label'=>'Export XL', 'url'=>array('errorExcel')),
);
?>

<h1><?php echo "Administrasi Persediaan (Stok)" ?></h1>

<h3><?php echo CHtml::link('Berdasarkan Kode Barang', 
		Yii::app()->createUrl('stockadmin/default/quantity'))?></h3> 

