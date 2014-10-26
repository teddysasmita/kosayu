<?php
/* @var $this AuthmanagerController */

$this->breadcrumbs=array(
	'Authmanager'=>array('/authmanager'),
	'Createtask',
);
?>
<h1>Create Task</h1>

<?php echo $this->renderPartial('_formitem', array('model'=>$model,'type'=>1)); ?>

<br>
<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
