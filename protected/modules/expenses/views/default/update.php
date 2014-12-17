<?php
/* @var $this ExpensesController */
/* @var $model Expenses */

$this->breadcrumbs=array(
    'Master Data'=>array('/site/masterdata'),
    'Daftar Biaya'=>array('index'),
    'Lihat Data'=>array('view','id'=>$model->id),
    'Ubah Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Lihat Data', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Cari Data', 'url'=>array('admin')),
);
?>

<h1>Biaya</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>