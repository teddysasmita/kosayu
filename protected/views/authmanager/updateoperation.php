<?php
/* @var $this AuthmanagerController */

$this->breadcrumbs=array(
	'Authmanager'=>array('/authmanager'),
	'Updateoperation',
);
?>
<h1>Update Operation <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_formitem', array('model'=>$model, 'type'=>0)); ?>

<p>
         You may change the content of this  by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
