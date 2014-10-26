<?php
/* @var $this AuthmanagerController */

$this->breadcrumbs=array(
	'Authmanager'=>array('/authmanager'),
	'Create Operation',
);
?>
<h1>Adopt a Role</h1>

<?php echo $this->renderPartial('_formadoptrole', array('model'=>$model,'parent'=>$name)); ?>

<br>
<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
