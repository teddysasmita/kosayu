<?php
/* @var $this SalespostransfersController */
/* @var $model Salespostransfers */

$this->breadcrumbs=array(
	'Cari Data'=>array('create'),
	'Lihat Data'
);

$this->menu=array(

);
?>

<h1>Daftar Penjualan Konsinyasi</h1>
<?php 
	if (isset($data)) {
		$count=count($data);
	
		$dataProvider=new CArrayDataProvider($data, array(
			'totalItemCount'=>$count,
			'keyField'=>'kdpenjualan'
		));
		$this->widget('zii.widgets.grid.CGridView', array(
			'dataProvider'=>$dataProvider,
			'columns'=>array(
					array(
						'header'=>'Tanggal',
						'name'=>'tglpenjualan',
						
					),
					array(
						'header'=>'No Faktur',
						'name'=>'kdpenjualan',
					),
					array(
						'header'=>'Nama Barang',
						'name'=>'nmkategori',
					),
					
					array(
						'header'=>'Jual',
						'name'=>'jmljual',
					),
					array(
						'header'=>'Harga Beli',
						'name'=>'hargabeli',
					),			
					
		),
		));
	};
	?>
	
<h2>
<?php 
	$mytotal = 0;
	foreach($data as $dt) {
		$mytotal += $dt['jmljual'] * $dt['hargabeli'];
	};
	
	echo "Total ".$mytotal; 
?>
</h2>
