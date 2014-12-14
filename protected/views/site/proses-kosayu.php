<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->pageTitle=Yii::app()->name;
?>

<h1>Proses</h1>

<h2>Bagian Pembelian</h2>
<h3><?php echo CHtml::link('Pemesanan ke Pemasok', Yii::app()->createUrl('purchasesorder'))?></h3>
<h3><?php echo CHtml::link('Pembelian dari Pemasok', Yii::app()->createUrl('purchases'))?></h3>
<h3><?php echo CHtml::link('Pembelian Konsinyasi ke Pemasok', Yii::app()->createUrl('consignpurchases'))?></h3>
<h3><?php echo CHtml::link('Retur Barang ke Pemasok', Yii::app()->createUrl('purchasesreturs'))?></h3>

<h2>Bagian Penjualan</h2>
<h3><?php echo CHtml::link('Penentuan Harga Jual', Yii::app()->createUrl('sellingprice'))?></h3>
<h3><?php echo CHtml::link('Perhitungan Konsinyasi', Yii::app()->createUrl('salespos/consignmentreport/create'))?></h3>
<h3><?php echo CHtml::link('Perhitungan Konsinyasi 2', Yii::app()->createUrl('salespos/consignmentreport/create2'))?></h3>
<h3><?php echo CHtml::link('Laporan Penjualan Global Berdasar Pemasok', Yii::app()->createUrl('salespos/salesposreport/create3'))?></h3>
<h3><?php echo CHtml::link('Laporan Penjualan Tiap Pemasok', Yii::app()->createUrl('salespos/salesposreport/create4'))?></h3>

<h2>Bagian Keuangan</h2>
<h3><?php echo CHtml::link('Penentuan Nilai Tukar Mata Uang Asing', Yii::app()->createUrl('currencyrates'))?></h3>
<h3><?php echo CHtml::link('Pembayaran Komisi Rekanan / Agen', Yii::app()->createUrl('tippayment'))?></h3>
<h3><?php echo CHtml::link('Laporan Pendapatan Kasir', Yii::app()->createUrl('salespos/salesposreport/create'))?></h3>
<h3><?php echo CHtml::link('Laporan Detil Pendapatan Kasir', Yii::app()->createUrl('salespos/salesposreport/create2'))?></h3>

