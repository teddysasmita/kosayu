<?php
/* @var $this SalesordersController */
/* @var $model Salesorders */

$this->breadcrumbs=array(
	//'Salesorders'=>array('index'),
	'Create',
);

$this->menu=array(
	//array('label'=>'List Salesorders', 'url'=>array('index')),
	//array('label'=>'Manage Salesorders', 'url'=>array('admin')),
      array('label'=>'Add Detail', 'url'=>array('detailsalesorders/create', 'id'=>$model->id),
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

<h1>Create Salesorders</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'id'=>$model->id, 'command'=>'create')); ?>