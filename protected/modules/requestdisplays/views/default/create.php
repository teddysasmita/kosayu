<?php
/* @var $this RequestdisplaysController */
/* @var $model Requestdisplays */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	//array('label'=>'List Requestdisplays', 'url'=>array('index')),
	//array('label'=>'Manage Requestdisplays', 'url'=>array('admin')),
      array('label'=>'Tambah Detil', 'url'=>array('detailrequestdisplays/create', 'id'=>$model->id),
          'linkOptions'=>array('id'=>'adddetail')),      
);
$jq=<<<EOH
$(function(){
  $('#adddetail').click(function(event){
        var mainform;
        var hiddenvar;
        mainform=$('#requestdisplays-form');
        $('#command').val('adddetail');
        mainform.submit();
        event.preventDefault();
  });
}); 
EOH;
Yii::app()->clientScript->registerScript('myscript', $jq, CClientScript::POS_READY);
?>

<h1>Permintaan Barang Display</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'id'=>$model->id, 'command'=>'create')); ?>