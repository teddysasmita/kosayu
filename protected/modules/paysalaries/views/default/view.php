<?php
/* @var $this PaysalariesController */
/* @var $model Paysalaries */

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
         'url'=>array('/paysalariesorder/detailpaysalaries/deleted', 'id'=>$model->id)),
	array('label'=>'Cetak', 'url'=>array('print', 'id'=>$model->id)),
		
);
?>

<h1>Pembayaran Gaji Karyawan</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'regnum',
		'idatetime',
		'idorder',
		array(
              'label'=>'Nama Karyawan',
              'value'=>lookup::EmployeeNameFromID($model->idemployee)
            ),
		array(
               'label'=>'Userlog',
               'value'=>lookup::UserNameFromUserID($model->userlog),
            ),
		'datetimelog',
      
	),
)); ?>

<?php 
   $count=Yii::app()->db->createCommand("select count(*) from detailpaysalaries where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailpaysalaries where id='$model->id'";

   $dataProvider=new CSqlDataProvider($sql,array(
          'totalItemCount'=>$count,
          ));
   $this->widget('zii.widgets.grid.CGridView', array(
         'dataProvider'=>$dataProvider,
         'columns'=>array(  
         	array(
                  'header'=>'Komponen',
                  'name'=>'name',
              ),
         	array(
                  'header'=>'Jumlah',
                  'name'=>'amount',
                  'type'=>'number'
              ),
            array(
               'class'=>'CButtonColumn',
               'buttons'=> array(
                   'delete'=>array(
                    'visible'=>'false'
                   ),
                  'update'=>array(
                     'visible'=>'false'
                  )
               ),
               'viewButtonUrl'=>"Action::decodeViewDetailPaySalaryUrl(\$data, $model->regnum)",
            )
         ),
   ));
 ?>
