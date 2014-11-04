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
<h3><?php echo CHtml::link('Bank dan Pembiayaan', Yii::app()->createUrl('salespos/salesposbanks'))?></h3>
<h3><?php echo CHtml::link('Kartu Kredit dan Debit', Yii::app()->createUrl('salespos'))?></h3>
<h3><?php echo CHtml::link('Akun Transfer', Yii::app()->createUrl('salespos/salespostransfers'))?></h3>
<h3><?php echo CHtml::link('Mata Uang Asing', Yii::app()->createUrl('currencies'))?></h3>

