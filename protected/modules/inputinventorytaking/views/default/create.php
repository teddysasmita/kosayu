<?php
/* @var $this InputinventorytakingsController */
/* @var $model Inputinventorytakings */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
	'Tambah Data',
);

$this->menu=array(
	//array('label'=>'List Inputinventorytakings', 'url'=>array('index')),
	//array('label'=>'Manage Inputinventorytakings', 'url'=>array('admin')),
      array('label'=>'Tambah Detil', 'url'=>array('detailinputinventorytakings/create', 'id'=>$model->id),
          'linkOptions'=>array('id'=>'adddetail')),      
);
$jq=<<<EOH
$(function(){
  $('#adddetail').click(function(event){
        var mainform;
        var hiddenvar;
        mainform=$('#inputinventorytakings-form');
        $('#command').val('adddetail');
        mainform.submit();
        event.preventDefault();
  });
}); 
EOH;
Yii::app()->clientScript->registerScript('myscript', $jq, CClientScript::POS_READY);
?>

<h1>Input Stok Opname</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'id'=>$model->id, 'command'=>'create')); ?>