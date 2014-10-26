<?php
/* @var $this InputinventorytakingsController */
/* @var $model Inputinventorytakings */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Inputinventorytakings', 'url'=>array('index')),
	//array('label'=>'Create Inputinventorytakings', 'url'=>array('create')),
	//array('label'=>'View Inputinventorytakings', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Inputinventorytakings', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailinputinventorytakings/create', 'id'=>$model->id, 
      'command'=>'update'),
          'linkOptions'=>array('id'=>'adddetail')),     
);
?>

<h1>Input Stok Opname</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>