<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->pageTitle=Yii::app()->name;
?>

<h1>Master Data</h1>

<h3><?php echo CHtml::link('Barang Dagang', Yii::app()->createUrl('item'))?></h3>
<h3><?php echo CHtml::link('Pelanggan', Yii::app()->createUrl('customer'))?></h3>
<h3><?php echo CHtml::link('Pemasok', Yii::app()->createUrl('supplier'))?></h3>
<h3><?php echo CHtml::link('Gudang', Yii::app()->createUrl('warehouse'))?></h3>
<h3><?php echo CHtml::link('Stok Opname', Yii::app()->createUrl('inventorytaking'))?></h3>
<h3><?php echo CHtml::link('Bank', Yii::app()->createUrl('salespos/salesposbanks'))?></h3>
<h3><?php echo CHtml::link('Jaringan Kartu Kredit', Yii::app()->createUrl('salespos'))?></h3>
<h3><?php echo CHtml::link('Mesin EDC', Yii::app()->createUrl('salespos/salesposedcs'))?></h3>
<h3><?php echo CHtml::link('Mata Uang Asing', Yii::app()->createUrl('currencies'))?></h3>
<h3><?php echo CHtml::link('Kelompok Komisi Barang', Yii::app()->createUrl('itemtipgroup'))?></h3>
