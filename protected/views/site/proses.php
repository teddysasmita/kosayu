<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->pageTitle=Yii::app()->name;
?>

<h1>Proses</h1>

<h2>Bagian Pembelian</h2>
<h3><?php echo CHtml::link('Pesanan ke Pemasok', Yii::app()->createUrl('purchasesorder'))?></h3>
<h3><?php echo CHtml::link('Penerimaan Barang dari Pemasok', Yii::app()->createUrl('purchasesreceipt'))?></h3>
<h3><?php echo CHtml::link('Pembayaran ke Pemasok', Yii::app()->createUrl('purchasespayment'))?></h3>
<h3><?php echo CHtml::link('Memo Pembelian', Yii::app()->createUrl('purchasesmemo'))?></h3>
<h3><?php echo CHtml::link('Retur Pembelian', Yii::app()->createUrl('purchasesretur'))?></h3>
<h3><?php echo CHtml::link('* Penerimaan Barang', Yii::app()->createUrl('purchasesstockentries'))?></h3>
<h3><?php echo CHtml::link('* Pengembalian Barang ke Pemasok', Yii::app()->createUrl('returstocks'))?></h3>

<h2>Bagian Penjualan</h2>
<h3><?php echo CHtml::link('Pesanan Pembeli', Yii::app()->createUrl('salesorder'))?></h3>
<h3><?php echo CHtml::link('Pengiriman Barang ke Pelanggan', Yii::app()->createUrl('stockdeliveries/index'))?></h3>
<h3><?php echo CHtml::link('Penerimaan Pembayaran dari Pelanggan', Yii::app()->createUrl('receipts/index'))?></h3>
<h3><?php echo CHtml::link('Penentuan Harga Jual', Yii::app()->createUrl('sellingprice'))?></h3>
<h3><?php echo CHtml::link('Pembatalan Penjualan', Yii::app()->createUrl('salescancel'))?></h3>
<h3><?php echo CHtml::link('Perubahan Penjualan', Yii::app()->createUrl('salesreplace'))?></h3>
<h3><?php echo CHtml::link('Laporan Penjualan', Yii::app()->createUrl('salespos/salesposreport/create'))?></h3>

<h2>Bagian CS</h2>
<h3><?php echo CHtml::link('Pengiriman Barang Tanpa Transaksi', Yii::app()->createUrl('deliveryordersnt'))?></h3>
<h3><?php echo CHtml::link('Pengiriman Barang Dengan Transaksi', Yii::app()->createUrl('deliveryorders'))?></h3>
<h3><?php echo CHtml::link('Pengambilan Barang Pembeli', Yii::app()->createUrl('orderretrievals'))?></h3>
<h3><?php echo CHtml::link('Permintaan Barang Display', Yii::app()->createUrl('requestdisplays'))?></h3>
<h3><?php echo CHtml::link('Retur Penjualan', Yii::app()->createUrl('salesreturs'))?></h3>
<h3><?php echo CHtml::link('Pemindahan Barang', Yii::app()->createUrl('itemtransfers'))?></h3>


<h2>Bagian Gudang</h2>
<h3><?php echo CHtml::link('Barang Masuk (Terima Barang)', Yii::app()->createUrl('stockentries'))?></h3>
<h3><?php echo CHtml::link('Barang Keluar', Yii::app()->createUrl('stockexits'))?></h3>
<h3><?php echo CHtml::link('Input Stok Opname', Yii::app()->createUrl('inputinventorytaking'))?></h3>
<h3><?php echo CHtml::link('Cetak Barcode', Yii::app()->createUrl('barcodeprint'))?></h3>

<h2>Bagian Keuangan</h2>
<h3><?php echo CHtml::link('Pembayaran Pemasok', Yii::app()->createUrl('financepayment'))?></h3>
<h3><?php echo CHtml::link('Penentuan Harga Pokok Opname', Yii::app()->createUrl('inventorycosting'))?></h3>
