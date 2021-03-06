<?php
/* @var $this IdguideprintsController */
/* @var $model Idguideprints */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('index'),
   'Lihat Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
	array('label'=>'Sejarah', 'url'=>array('history', 'id'=>$model->id)),
	array('label'=>'Data Detil yang dihapus', 
         'url'=>array('/idguideprint/detailidguideprints/deleted', 'id'=>$model->id)),
	array('label'=>'Cetak', 'url'=>['printCards','id'=>$model->id],'linkOptions'=>['target'=>'_blank']),
	array('label'=>'Cetak Belakang', 'url'=>['printBacks','id'=>$model->id],'linkOptions'=>['target'=>'_blank']),
);
?>

<h1>Cetak Kartu Guide</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'regnum',
		'idatetime',
		'paperwidth',
		'paperheight',
		'labelwidth',
		'labelheight',
			
		array(
			'label'=>'Userlog',
			'value'=>lookup::UserNameFromUserID($model->userlog),
		),
		'datetimelog',
	),
)); ?>

<?php 
   $count=Yii::app()->db->createCommand("select count(*) from detailidguideprints where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailidguideprints where id='$model->id'";

   $dataProvider=new CSqlDataProvider($sql,array(
          'totalItemCount'=>$count,
          ));
   $this->widget('zii.widgets.grid.CGridView', array(
         'dataProvider'=>$dataProvider,
         'columns'=>array(
            array(
               'header'=>'Guide',
               'name'=>'idguide',
            	'value'=>"lookup::GuideNamefromID(\$data['idguide'])",
            ),
         ),
   ));
 ?>
