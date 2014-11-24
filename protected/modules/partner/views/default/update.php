<?php
/* @var $this PartnersController */
/* @var $model Partners */

$this->breadcrumbs=array(
      'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Partners', 'url'=>array('index')),
	//array('label'=>'Create Partners', 'url'=>array('create')),
	//array('label'=>'View Partners', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Partners', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailpartners/create', 
       'id'=>$model->id, 'command'=>'update'),
          'linkOptions'=>array('id'=>'adddetail')), 
);
?>

<h1>Rekanan / Agen</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>