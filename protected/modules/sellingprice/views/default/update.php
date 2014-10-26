<?php
/* @var $this SellingpricesController */
/* @var $model Sellingprices */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*array('label'=>'List Sellingprices', 'url'=>array('index')),
	array('label'=>'Create Sellingprices', 'url'=>array('create')),
	array('label'=>'View Sellingprices', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Sellingprices', 'url'=>array('admin')),*/
);
?>

<h1>Penentuan Harga Jual</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>