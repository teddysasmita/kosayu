<?php
/* @var $this RequestdisplaysController */
/* @var $model Requestdisplays */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Requestdisplays', 'url'=>array('index')),
	//array('label'=>'Create Requestdisplays', 'url'=>array('create')),
	//array('label'=>'View Requestdisplays', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Requestdisplays', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailrequestdisplays/create', 'id'=>$model->id, 
      'command'=>'update'),
          'linkOptions'=>array('id'=>'adddetail')),     
);
?>

<h1>Permintaan Barang Display</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>