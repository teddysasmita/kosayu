<?php
/* @var $this EmployeesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
   'Master Data'=>array('/site/masterdata'),
	'Daftar',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
   array('label'=>'Data yang telah terhapus', 'url'=>array('deleted')),
		array('label'=>'Export Nama ke Xcl', 'url'=>array('export2xcl')),
);
?>

<h1>Karyawan</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
