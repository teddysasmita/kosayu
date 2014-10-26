<?php
/* @var $this AuthmanagerController */

$this->breadcrumbs=array(
	'Authorization Manager',
);

$this->menu=array(
	array('label'=>'Create Operation', 'url'=>array('createoperation')),
	array('label'=>'Create Task', 'url'=>array('createtask')),
      array('label'=>'Create Role', 'url'=>array('createrole'))
);
?>

<br><h1>Roles List</h1>

<?php

   $dataProvider= new CActiveDataProvider('AuthItem', array(
      'criteria'=>array(
         'condition'=>'type=2'
      ),
   ));
   $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_viewrole',
));

?>
 

<br><h1>Tasks List</h1>

<?php

   $dataProvider= new CActiveDataProvider('AuthItem', array(
      'criteria'=>array(
         'condition'=>'type=1'
      ),
   ));
   $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_viewtask',
));

?>

<br><h1>Operations List</h1>

<?php

   $dataProvider= new CActiveDataProvider('AuthItem', array(
      'criteria'=>array(
         'condition'=>'type=0'
      ),
   ));
   $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_viewoperation'
));

?>  

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
