<?php
/* @var $this AuthmanagerController */

$this->breadcrumbs=array(
   'Penyesuaian'=>array('/site/setting'),
	'Daftar'=>array('index'),
   'Lihat Data'=>array('/user/default/view', 'id'=>$userid),
	'Pemberian Hak Tugas',
);
?>
<h1>Penyesuaian Operator</h1>

<?php echo $this->renderPartial('_formassigntask', array('model'=>$model,'userid'=>$userid)); ?>

<br>
<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
