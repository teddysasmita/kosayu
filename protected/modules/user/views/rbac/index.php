<?php
/* @var $this AuthmanagerController */

$this->breadcrumbs=array(
	'Penyesuaian'=>array('/site/setting'),
   'Daftar'
);

$this->menu=array(
	array('label'=>'Tambah Hak Operasi', 'url'=>array('createoperation')),
	array('label'=>'Tambah Hak Tugas', 'url'=>array('createtask')),
      array('label'=>'Tambah Peran', 'url'=>array('createrole'))
);
?>

<br><h1>Daftar Hak Peran</h1>

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
 

<br><h1>Daftar Hak Tugas</h1>

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

<br><h1>Daftar Hak Operasi</h1>

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
