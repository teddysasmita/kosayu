<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->pageTitle=Yii::app()->name;
?>

<h1>Proses</h1>

<h2>Bagian Pembelian</h2>
<h3><?php echo CHtml::link('Pemesanan ke Pemasok', Yii::app()->createUrl('purchasesorders'))?></h3>
<h3><?php echo CHtml::link('Penerimaan Barang dari Pemasok', Yii::app()->createUrl('purchasesorders'))?></h3>
<h3><?php echo CHtml::link('Retur Barang ke Pemasok', Yii::app()->createUrl('purchasesreturs'))?></h3>

<h2>Bagian Penjualan</h2>


<h2>Bagian Gudang</h2>


<h2>Bagian Keuangan</h2>
<h3><?php echo CHtml::link('Penentuan Nilai Tukar Mata Uang Asing', Yii::app()->createUrl('currencyrates'))?></h3>
