<?php
/* @var $this AuthsController */
/* @var $model Auths */

$this->breadcrumbs=array(
	'Users'=>array('users/view', 'id'=>$iduser),
	'Create',
);

/*
 $this->menu=array(
	array('label'=>'List Auths', 'url'=>array('index')),
	array('label'=>'Manage Auths', 'url'=>array('admin')),
);
*/
?>

<h1>Create Auths</h1>

<?php 
   $model->iduser=$iduser;
   echo $this->renderPartial('_form', array('model'=>$model)); 
?>