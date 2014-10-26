<?php
/* @var $this StockentriesController */
/* @var $model Stockentries */

$this->breadcrumbs=array(
	'Stockentries'=>array('index'),
	'Create',
);

$this->menu=array(
    /*
	array('label'=>'List Stockentries', 'url'=>array('index')),
	array('label'=>'Manage Stockentries', 'url'=>array('admin')),
    */
    array('label'=>'Add Detail', 'url'=>array('detailstockentries/create', 'id'=>$model->id),
          'linkOptions'=>array('id'=>'adddetail')),   
);
$jq=<<<EOH
$(function(){
  $('#adddetail').click(function(event){
        var mainform;
        var hiddenvar;
        mainform=$('#stockentries-form');
        $('#command').val('adddetail');
        mainform.submit();
        event.preventDefault();
  });
}); 
EOH;
Yii::app()->clientScript->registerScript('myscript', $jq, CClientScript::POS_READY);

?>

<h1>Create Stockentries</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'id'=>$model->id, 'command'=>'create')); ?>