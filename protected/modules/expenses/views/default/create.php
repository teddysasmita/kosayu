<?php
/* @var $this ExpensesController */
/* @var $model Expenses */

$this->breadcrumbs=array(
   'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	//array('label'=>'Daftar Biaya', 'url'=>array('index')),
	array('label'=>'Cari Biaya', 'url'=>array('admin')),
);
?>

<h1>Biaya</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>