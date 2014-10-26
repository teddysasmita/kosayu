<?php
/* @var $this PaymentsController */
/* @var $model Payments */

$this->breadcrumbs=array(
	'Payments'=>array('index'),
	'Create',
);

$this->menu=array(
	/*array('label'=>'List Payments', 'url'=>array('index')),
	array('label'=>'Manage Payments', 'url'=>array('admin')),*/
      array('label'=>'Add Detail', 'url'=>array('detailpayments/create', 'id'=>$model->id),
          'linkOptions'=>array('id'=>'adddetail')),      
);
$jq=<<<EOH
$(function(){
  $('#adddetail').click(function(event){
        var mainform;
        var hiddenvar;
        mainform=$('#payments-form');
        $('#command').val('adddetail');
        mainform.submit();
        event.preventDefault();
  });
}); 
EOH;
Yii::app()->clientScript->registerScript('myscript', $jq, CClientScript::POS_READY);
?>

<h1>Create Payments</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'id'=>$model->id, 'command'=>'create')); ?>