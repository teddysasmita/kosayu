<?php
/* @var $this CurrencyratesController */
/* @var $model Currencyrates */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*array('label'=>'List Currencyrates', 'url'=>array('index')),
	array('label'=>'Create Currencyrates', 'url'=>array('create')),
	array('label'=>'View Currencyrates', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Currencyrates', 'url'=>array('admin')),*/
);
?>

<h1>Penentuan Nilai Tukar Mata Uang Asing</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>