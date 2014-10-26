<?php
/* @var $this StockdeliveriesController */
/* @var $model Stockdeliveries */

$this->breadcrumbs=array(
	'Stockdeliveries'=>array('index'),
	'Create',
);

$this->menu=array(
	/*array('label'=>'List Stockdeliveries', 'url'=>array('index')),
	array('label'=>'Manage Stockdeliveries', 'url'=>array('admin')),*/
    array('label'=>'Add Detail', 'url'=>array('detailstockdeliveries/create', 'id'=>$model->id),
          'linkOptions'=>array('id'=>'adddetail')), 
);

$jq=<<<EOH
$(function(){
  $('#adddetail').click(function(event){
        var mainform;
        var hiddenvar;
        mainform=$('#stockdeliveries-form');
        $('#command').val('adddetail');
        mainform.submit();
        event.preventDefault();
  });
}); 
EOH;
Yii::app()->clientScript->registerScript('myscript', $jq, CClientScript::POS_READY);

?>

<h1>Create Stockdeliveries</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'id'=>$model->id, 'command'=>'create')); ?>