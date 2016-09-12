<?php
/* @var $this StickertoguidesController */
/* @var $model Stickertoguides */

$this->breadcrumbs=array(
   'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	//array('label'=>'Daftar Pelanggan', 'url'=>array('index')),
	array('label'=>'Cari Data', 'url'=>array('admin')),
);
?>

<h1>Sticker ke Guide</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>