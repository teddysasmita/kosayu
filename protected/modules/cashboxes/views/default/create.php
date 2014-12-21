<?php
/* @var $this CashboxesController */
/* @var $model Cashboxes */

$this->breadcrumbs=array(
   'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	//array('label'=>'Daftar Akun Kas', 'url'=>array('index')),
	array('label'=>'Cari Akun Kas', 'url'=>array('admin')),
);
?>

<h1>Akun Kas</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>