<?php
/* @var $this AuthmanagerController */

$this->breadcrumbs=array(
	'Authorization Manager'=>array('/authmanager'),
	'View Task',
);

$this->menu=array(
	array('label'=>'Update Task', 'url'=>array('updatetask', 'name'=>$model->name)),
	array('label'=>'Delete Task', 'url'=>'#', 'linkOptions'=>array('submit'=>array('deletetask','name'=>$model->name),
            'confirm'=>'Are you sure you want to delete this Task?')),
      array('label'=>'Adopt an Operation', 'url'=>array('adoptoperation', 'name'=>$model->name, 
            'type'=>$model->type)),
      array('label'=>'Adopt a Task', 'url'=>array('adopttask', 'name'=>$model->name, 
            'type'=>$model->type)),
);

?>
<h1>View Task #<?php echo $model->name;; ?></h1>

<?php

   $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
            'description',
	),
)); ?>

<br><h1/>Operation Children List #<?php echo $model->name; ?></h1>

<?php 
   $count=Yii::app()->db->createCommand("select count(*) from AuthItemChild a join AuthItem b on b.name=a.child where a.parent='$model->name' and b.type=0")->queryScalar();
   $sql='select a.id, a.child, b.type, b.description from AuthItemChild a join AuthItem b on b.name=a.child '.
           "where a.parent='$model->name' and b.type=0";
   $dataProvider=new CSqlDataProvider($sql,array(
       'totalItemCount'=>$count,
       )
   );
      
   $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
      'columns'=>array(
           array(
               'header'=>'Name',
               'name'=>'child',
           ),
          array(
              'header'=>'Jenis',
              'name'=>'type',
          ),
          array(
              'header'=>'Deskripsi',
              'name'=>'description',
          ),
          array(
              'class'=>'CButtonColumn',
              'buttons'=>array(
                  'view'=>array(
                      'visible'=>'false',
                  ),
                  'update'=>array(
                      'visible'=>'false',
                  ),
              ),
              'deleteButtonUrl'=>"idmaker::decodeDeleteAdoptedOperationUrl(\$data)"
          )
      )
   )); 
?>

<br><h1/>Task Children List #<?php echo $model->name; ?></h1>

<?php 
   $count=Yii::app()->db->createCommand("select count(*) from AuthItemChild a join AuthItem b on b.name=a.child where a. parent='$model->name' and b.type=1")->queryScalar();
   $sql='select a.id, a.child, b.type, b.description from AuthItemChild a join AuthItem b on b.name=a.child '.
           "where a.parent='$model->name' and b.type=1";
   $dataProvider=new CSqlDataProvider($sql,array(
       'totalItemCount'=>$count,
       )
   );
      
   $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
      'columns'=>array(
           array(
               'header'=>'Name',
               'name'=>'child',
           ),
          array(
              'header'=>'Jenis',
              'name'=>'type',
          ),
          array(
              'header'=>'Deskripsi',
              'name'=>'description',
          ),
          array(
              'class'=>'CButtonColumn',
              'buttons'=>array(
                  'view'=>array(
                      'visible'=>'false',
                  ),
                  'update'=>array(
                      'visible'=>'false',
                  ),
              ),
              'deleteButtonUrl'=>"idmaker::decodeDeleteAdoptedTaskUrl(\$data)"
          )
      )
   )); 
?>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
