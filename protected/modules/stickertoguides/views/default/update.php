<?php
/* @var $this StickertoguidesController */
/* @var $model Stickertoguides */

$this->breadcrumbs=array(
    'Master Data'=>array('/site/masterdata'),
    'Daftar Pencatatan Sticker ke Guide'=>array('index'),
    'Lihat Data'=>array('view','id'=>$model->id),
    'Ubah Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Lihat Data', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Cari Data', 'url'=>array('admin')),
);
?>

<h1>Pencatatan Sticker ke Guide</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>