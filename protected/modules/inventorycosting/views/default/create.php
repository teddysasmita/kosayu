<?php
/* @var $this InventorycostingsController */
/* @var $model Inventorycostings */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	//array('label'=>'Daftar Pelanggan', 'url'=>array('index')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Penentuan Harga Pokok Opname</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>