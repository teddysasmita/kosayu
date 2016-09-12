<?php
/* @var $this DetailidguideprintsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailidguideprints',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Cari Data', 'url'=>array('admin')),
);
?>

<h1>Detil Cetak Kartu Guide</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
