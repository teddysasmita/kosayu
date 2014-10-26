<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
	'Penyesuaian'=>array('/site/setting'),
	'Daftar'=>array('index'),
   'Lihat Data'
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 
          'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),
              'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
      array('label'=>'Pemberian Hak Operasi', 'url'=>array('assignoperation','id'=>$model->id)),
    //array('label'=>'Add Operation', 'url'=>array('assignoperation','id'=>$model->id)),
    array('label'=>'Pemberian Hak Tugas', 'url'=>array('assigntask','id'=>$model->id)),
    array('label'=>'Pemberian Hak Peran', 'url'=>array('assignrole','id'=>$model->id)),
    array('label'=>'Allowed Hosts', 'url'=>array('allowedhosts','id'=>$model->id)),
);
?>

<h1>Penyesuaian Operator</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'loginname',
		'fullname',
		array(
                    'label'=>'Kondisi',
                    'value'=>idmaker::lookUpUserState($model->active),
                ),
		'userlog',
		'datetimelog',
	),
)); ?>

<br><h2>Daftar Hak Operasi</h2>

<?php 
   $count=Yii::app()->authdb->createCommand("select count(*) from AuthAssignment a join AuthItem b on b.name=a.itemname where userid='$model->id' and b.type=0")->queryScalar();
   $sql='select a.id, a.userid, a.itemname, b.type, b.description from AuthAssignment a join AuthItem b on b.name=a.itemname '.
           "where a.userid='$model->id' and b.type=0";
   $dataProvider=new CSqlDataProvider($sql,array(
       'totalItemCount'=>$count,
       'db'=>Yii::app()->authdb, 
       )
   );
      
   $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
      'columns'=>array(
           array(
               'header'=>'Name',
               'name'=>'itemname',
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
              'deleteButtonUrl'=>"idmaker::decodeDeleteAssignedOperationUrl(\$data)"
          )
      )
   )); 
?>

<br><h2>Daftar Hak Tugas</h2>

<?php 
   $count=Yii::app()->authdb->createCommand("select count(*) from AuthAssignment a join AuthItem b on b.name=a.itemname where userid='$model->id' and b.type=1")->queryScalar();
   $sql='select a.id, a.userid, a.itemname, b.type, b.description from AuthAssignment a join AuthItem b on b.name=a.itemname '.
           "where a.userid='$model->id' and b.type=1";
   $dataProvider=new CSqlDataProvider($sql,array(
       'totalItemCount'=>$count,
      'db'=>Yii::app()->authdb, 
      )
   );
      
   $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
      'columns'=>array(
           array(
               'header'=>'Name',
               'name'=>'itemname',
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
              'deleteButtonUrl'=>"idmaker::decodeDeleteAssignedTaskUrl(\$data)"
          )
      )
   )); 
?>

<br><h2>Daftar Hak Peran</h2>

<?php 
   $count=Yii::app()->authdb->createCommand("select count(*) from AuthAssignment a join AuthItem b on b.name=a.itemname where userid='$model->id' and b.type=2")->queryScalar();
   $sql='select a.id, a.userid, a.itemname, b.type, b.description from AuthAssignment a join AuthItem b on b.name=a.itemname '.
           "where a.userid='$model->id' and b.type=2";
   $dataProvider=new CSqlDataProvider($sql,array(
       'totalItemCount'=>$count,
      'db'=>Yii::app()->authdb, 
      )
   );
      
   $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
      'columns'=>array(
           array(
               'header'=>'Name',
               'name'=>'itemname',
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
              'deleteButtonUrl'=>"idmaker::decodeDeleteAssignedRoleUrl(\$data)"
          )
      )
   )); 
?>
