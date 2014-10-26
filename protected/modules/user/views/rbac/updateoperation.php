<?php
/* @var $this AuthmanagerController */

$this->breadcrumbs=array(
   'Penyesuaian'=>array('/site/setting'),
   'Daftar'=>array('/user/rbac'),
	'Lihat Data Hak Operasi'=>array('/user/rbac/viewoperation', 'name'=>$name),
	'Ubah Data',
);
?>
<h1>Penyesuaian Otoritas</h1>

<?php echo $this->renderPartial('_formitem', array('model'=>$model, 'type'=>0)); ?>

<p>
         You may change the content of this  by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
