<?php
/* @var $this AuthmanagerController */

$this->breadcrumbs=array(
	'Penyesuaian'=>array('/site/setting'),
   'Daftar'=>array('/user/rbac'),
	'Lihat Data Hak Operasi'
);

$this->menu=array(
	array('label'=>'Ubah Data', 'url'=>array('updateoperation', 'name'=>$model->name)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('deleteoperation','name'=>$model->name),
            'confirm'=>'Are you sure you want to delete this Operation?')),
      array('label'=>'Memasukkan Hak Operasi', 'url'=>array('adoptoperation', 'name'=>$model->name, 
            'type'=>$model->type))
);
?>

<h1>Penyesuaian Otoritas</h1>

<?php 
   $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
            'description',
	),
)); ?>

<br><h2>Daftar Definisi</h2>

<?php 
   $count=Yii::app()->authdb->createCommand("select count(*) from AuthItemChild a join AuthItem b on b.name=a.child where a.parent='$model->name' and b.type=0")->queryScalar();
   $sql='select a.id, a.child, b.type, b.description from AuthItemChild a join AuthItem b on b.name=a.child '.
           "where a.parent='$model->name' and b.type=0";
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
