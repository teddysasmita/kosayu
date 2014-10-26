<?php
/* @var $this RetrievalreplacesController */
/* @var $model Retrievalreplaces */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	/* 
	array('label'=>'Daftar', 'url'=>array('index')),
	array('label'=>'Pengaturan', 'url'=>array('admin')),
      array('label'=>'Tambah Detil', 'url'=>array('detailretrievalreplaces/create', 
         'id'=>$model->id),
          'linkOptions'=>array('id'=>'adddetail')), 
	*/
);

$jq=<<<EOH
   $('#adddetail').click(function(event){
     var mainform;
     var hiddenvar;
     mainform=$('#retrievalreplaces-form');
     $('#command').val('adddetail');
     mainform.submit();
     event.preventDefault();
   });
EOH;
Yii::app()->clientScript->registerScript('myscript', $jq, CClientScript::POS_READY);
?>

<h1>Penukaran Pengambilan Barang</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'id'=>$model->id, 'error' => $error, 'command'=>'create')); ?>