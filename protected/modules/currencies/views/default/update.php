<?php
/* @var $this CurrenciesController */
/* @var $model Currencies */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*array('label'=>'List Currencies', 'url'=>array('index')),
	array('label'=>'Create Currencies', 'url'=>array('create')),
	array('label'=>'View Currencies', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Currencies', 'url'=>array('admin')),*/
);
?>

<h1>Mata Uang Asing</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>