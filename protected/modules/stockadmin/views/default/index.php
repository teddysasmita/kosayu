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

<h3><?php echo CHtml::link('Berdasarkan Kuantitas dan Lokasi', 
		Yii::app()->createUrl('stockadmin/default/quantity'))?></h3> 
<h3><?php echo CHtml::link('Berdasarkan Nomor Seri dan Lokasi', 
		Yii::app()->createUrl('stockadmin/default/serial'))?></h3>
<h3><?php echo CHtml::link('Kartu Stok berdasarkan Nama Barang dan Gudang', 
		Yii::app()->createUrl('stockadmin/default/flow'))?></h3>
<h3><?php echo CHtml::link('Daftar Stok Error', 
		Yii::app()->createUrl('stockadmin/default/indexError'))?></h3>
<h3><?php echo CHtml::link('Lacak Nomor Seri', 
		Yii::app()->createUrl('stockadmin/default/trace'))?></h3>
