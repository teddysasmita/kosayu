<?php
/* @var $this CustomersController */
/* @var $model Customers */

$this->breadcrumbs=array(
   'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	//array('label'=>'Daftar Pelanggan', 'url'=>array('index')),
	array('label'=>'Cari Pelanggan', 'url'=>array('admin')),
);
?>

<h1>Pelanggan</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>