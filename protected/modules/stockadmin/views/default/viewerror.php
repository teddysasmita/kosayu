<?php
/* @var $this StockentriesController */
/* @var $model Stockentries */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('index'),
   'Lihat Data',
);

$this->menu=array(
   array('label'=>'Export XL', 'url'=>array('errorExcel', 'id'=>$model->id))
);
?>

<h1>Error Stok Keluar</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'regnum',
		'idatetime',
		array(
			'label'=>'Userlog',
			'value'=>lookup::UserNameFromUserID($model->userlog),
		),
		'datetimelog',
	),
)); ?>

<?php 
   $count=Yii::app()->db->createCommand("select count(*) from detailerrors where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailerrors where id='$model->id'";
   $datas = Yii::app()->db->createCommand($sql)->queryAll();
   foreach ($datas as $data) {
   		$newdata['iddetail'] = $data['iddetail'];
   		$newdata['iditem'] = $data['iditem'];
   		$newdata['serialnum'] = $data['serialnum'];
   		$temp = explode('-', $data['remark']);
   		$newdata['wh'] = lookup::WarehouseNameFromWarehouseID(trim($temp[2]));
		$newdata['regnum'] = trim($temp[0]);
   		$newdatas[] = $newdata;
   }

   $dataProvider=new CArrayDataProvider($newdatas,array(
          'totalItemCount'=>$count,
   			'keyField'=>'iddetail',
   ));
   $this->widget('zii.widgets.grid.CGridView', array(
         'dataProvider'=>$dataProvider,
         'columns'=>array(
            array(
               'header'=>'Barang',
               'name'=>'iditem',
               'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])"
            ),
            array(
              'header'=>'Serial Number',
              'name'=>'serialnum',
            ),
         	array(
				'header'=>'Gudang',
				'name'=>'wh',
         	),
         	array(
         		'header'=>'Transaksi',
         		'name'=>'regnum',
         	),
         	/*
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
                  'viewButtonUrl'=>"Action::decodeViewDetailStockEntryUrl(\$data)",
              )
             */
         ),
   ));
 ?>
