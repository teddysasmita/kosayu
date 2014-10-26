<?php
/* @var $this AuthmanagerController */

$this->breadcrumbs=array(
	'Authmanager'=>array('/authmanager'),
	'Updatetask',
);
?>
<h1>Update Task <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_formitem', array('model'=>$model, 'type'=>1)); ?>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
