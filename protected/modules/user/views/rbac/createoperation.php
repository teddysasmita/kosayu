<?php
/* @var $this RBACController */

$this->breadcrumbs=array(
   'Penyesuaian'=>array('/site/setting'),
   'Daftar'=>array('/user/rbac'),
	'Tambah Data Hak Operasi',
);
?>
<h1>Penyesuaian Otoritas</h1>

<?php echo $this->renderPartial('_formitem', array('model'=>$model,'type'=>0)); ?>

<br>
<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
