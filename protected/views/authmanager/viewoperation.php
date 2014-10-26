<?php
/* @var $this AuthmanagerController */

$this->breadcrumbs=array(
	'Authorization Manager'=>array('/authmanager'),
	'View Operation',
);

$this->menu=array(
	array('label'=>'Update Operation', 'url'=>array('updateoperation', 'name'=>$model->name)),
	array('label'=>'Delete Operation', 'url'=>'#', 'linkOptions'=>array('submit'=>array('deleteoperation','name'=>$model->name),
            'confirm'=>'Are you sure you want to delete this Operation?')),
      array('label'=>'Adopt an Operation', 'url'=>array('adoptoperation', 'name'=>$model->name, 
            'type'=>$model->type))
);
?>

<h1>View Operation #<?php echo $model->name; ?></h1>

<?php 
   $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
            'description',
	),
)); ?>

<br><h1/>Children List #<?php echo $model->name; ?></h1>

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


<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
