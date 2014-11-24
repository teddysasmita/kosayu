<?php
/* @var $this ItemtipgroupsController */
/* @var $model Itemtipgroups */

$this->breadcrumbs=array(
     'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Itemtipgroups', 'url'=>array('index')),
	//array('label'=>'Create Itemtipgroups', 'url'=>array('create')),
	//array('label'=>'View Itemtipgroups', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Itemtipgroups', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailitemtipgroups/create', 
       'id'=>$model->id, 'command'=>'update'),
          'linkOptions'=>array('id'=>'adddetail')), 
);
?>

<h1>Kelompok Komisi Barang</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>