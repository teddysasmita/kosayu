<?php
/* @var $this ReceiptsController */
/* @var $model Receipts */

$this->breadcrumbs=array(
	'Receipts'=>array('index'),
	'Create',
);

$this->menu=array(
	/*array('label'=>'List Receipts', 'url'=>array('index')),
	array('label'=>'Manage Receipts', 'url'=>array('admin')),*/
      array('label'=>'Add Detail', 'url'=>array('detailreceipts/create', 'id'=>$model->id),
          'linkOptions'=>array('id'=>'adddetail')),      
);
$jq=<<<EOH
$(function(){
  $('#adddetail').click(function(event){
        var mainform;
        var hiddenvar;
        mainform=$('#receipts-form');
        $('#command').val('adddetail');
        mainform.submit();
        event.preventDefault();
  });
}); 
EOH;
Yii::app()->clientScript->registerScript('myscript', $jq, CClientScript::POS_READY);
?>

<h1>Create Receipts</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'id'=>$model->id, 'command'=>'create')); ?>