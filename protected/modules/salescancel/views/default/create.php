<?php
/* @var $this SalescancelController */
/* @var $model Salescancel */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	//array('label'=>'Daftar Pembatalan Penjualan', 'url'=>array('index')),
	array('label'=>'Cari Pembatalan Penjualan', 'url'=>array('admin')),
);
?>

<h1>Pembatalan Penjualan</h1>

<?php
	if (isset($rawdata))
		echo $this->renderPartial('_form', array('model'=>$model, 'rawdata'=>$rawdata ));
	else
		echo $this->renderPartial('_form', array('model'=>$model));
?>