<?php
/* @var $this AuthmanagerController */

$this->breadcrumbs=array(
	'Penyesuaian'=>array('/site/setting'),
   'Daftar'=>array('/user/rbac'),
	'Lihat Data Hak Peran'=>array('/user/rbac/viewrole', 'name'=>$name),
	'Ubah Data',
);
?>
<h1>Penyesuaian Otoritas</h1>

<?php echo $this->renderPartial('_formitem', array('model'=>$model, 'type'=>2)); ?>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
