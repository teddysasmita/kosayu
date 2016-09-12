<?php
/* @var $this StickertoguidesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    'Master Data'=>array('/site/masterdata'),
    'Daftar',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Cari Data', 'url'=>array('admin')),
   array('label'=>'Terhapus', 'url'=>array('deleted')),
   
);
?>

<h1>Pencatatan Sticker ke Guide</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
