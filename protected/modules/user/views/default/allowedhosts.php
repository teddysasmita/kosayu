<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Add Operation', 'url'=>array('assignoperation','id'=>$model->id)),
);
?>

<br><h1/>Allowed Host #<?php echo $id; ?></h1>

<?php 
   $count=Yii::app()->authdb->createCommand("select count(*) from userhost where iduser='$id'")->queryScalar();
   $sql="select * from userhost where iduser='$id'";
   $dataProvider=new CSqlDataProvider($sql,array(
       'totalItemCount'=>$count,
       'db'=>Yii::app()->authdb, 
       )
   );
      
   $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
      'columns'=>array(
           array(
               'header'=>'Nama Pengguna',
               'name'=>'iduser',
              'value'=>'lookup::UserNameFromUserID($data->iduser)'
           ),
          array(
              'header'=>'Alamat IP',
              'name'=>'allowedip',
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
              'deleteButtonUrl'=>"idmaker::decodeDeleteAssignedOperationUrl(\$data)"
          )
      )
   )); 
?>
