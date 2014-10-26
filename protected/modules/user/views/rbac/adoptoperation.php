<?php
/* @var $this AuthmanagerController */

switch ($type) {
   case 0:
      $typename='viewoperation';
      break;
   case 1:
      $typename='viewtask';
      break;
   case 2:
      $typename='viewrole';
      break;
}
$this->breadcrumbs=array(
   'Penyesuaian'=>array('/site/setting'),
   'Daftar'=>array('/user/rbac'),
   'Lihat Data'=>array("/user/rbac/$typename", 'name'=>$name),
   'Memasukkan Hak Operasi',
);
?>
<h1>Penyesuaian Otoritas</h1>

<?php echo $this->renderPartial('_formadoptoperation', array('model'=>$model,'parent'=>$name)); ?>

<br>
<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
