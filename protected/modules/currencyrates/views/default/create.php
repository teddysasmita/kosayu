<?php
/* @var $this CurrencyratesController */
/* @var $model Currencyrates */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Penentuan Nilai Tukar Mata Uang Asing</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>