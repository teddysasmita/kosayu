<?php
/* @var $this AuthmanagerController */

$this->breadcrumbs=array(
	'Authmanager'=>array('/authmanager'),
	'Updaterole',
);
?>
<h1>Update Role <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_formitem', array('model'=>$model, 'type'=>2)); ?>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
