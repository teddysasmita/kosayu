<?php
/* @var $this SalesordersController */
/* @var $model Salesorders */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
	'Tambah Data',
);

$this->menu=array(
	//array('label'=>'List Salesorders', 'url'=>array('index')),
	//array('label'=>'Manage Salesorders', 'url'=>array('admin')),
      array('label'=>'Tambah Detil', 'url'=>array('detailsalesorders/create', 'id'=>$model->id),
          'linkOptions'=>array('id'=>'adddetail')),      
);
$jq=<<<EOH
$(function(){
  $('#adddetail').click(function(event){
        var mainform;
        var hiddenvar;
        mainform=$('#salesorders-form');
        $('#command').val('adddetail');
        mainform.submit();
        event.preventDefault();
  });
}); 
EOH;
Yii::app()->clientScript->registerScript('myscript', $jq, CClientScript::POS_READY);
?>

<h1>Pemesanan oleh Pelanggan</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'id'=>$model->id, 'command'=>'create')); ?>