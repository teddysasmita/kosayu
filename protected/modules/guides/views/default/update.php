<?php
/* @var $this GuidesController */
/* @var $model Guides */

$this->breadcrumbs=array(
    'Master Data'=>array('/site/masterdata'),
    'Daftar Pemandu / Guide'=>array('index'),
    'Lihat Data'=>array('view','id'=>$model->id),
    'Ubah Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Lihat Data', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Cari Data', 'url'=>array('admin')),
);
?>

<h1>Pemandu / Guide</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>