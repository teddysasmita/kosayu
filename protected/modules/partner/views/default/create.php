<?php
/* @var $this PartnersController */
/* @var $model Itemtipgroups */

$this->breadcrumbs=array(
      'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	//array('label'=>'Daftar', 'url'=>array('index')),
	array('label'=>'Pengaturan', 'url'=>array('admin')),
      array('label'=>'Tambah Detil', 'url'=>array('detailpartners/create', 
         'id'=>$model->id),
          'linkOptions'=>array('id'=>'adddetail')), 
);

$jq=<<<EOH
   $('#adddetail').click(function(event){
     var mainform;
     var hiddenvar;
     mainform=$('#partners-form');
     $('#command').val('adddetail');
     mainform.submit();
     event.preventDefault();
   });
EOH;
Yii::app()->clientScript->registerScript('myscript', $jq, CClientScript::POS_READY);
?>

<h1>Rekanan / Agen</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'id'=>$model->id, 'command'=>'create')); ?>