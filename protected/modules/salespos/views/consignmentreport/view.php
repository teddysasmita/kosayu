<?php
/* @var $this SalespostransfersController */
/* @var $model Salespostransfers */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
	'Lihat Data'
);

$this->menu=array(

);
?>

<h1>Daftar Penjualan Konsinyasi</h1>

<h2>
<?php	
	echo $suppliername; 
?>
</h2>

<?php 
	if (isset($data)) {
		$count=count($data);
	
		$dataProvider=new CArrayDataProvider($data, array(
			'totalItemCount'=>$count,
			'keyField'=>'id'
		));
		$this->widget('zii.widgets.grid.CGridView', array(
			'dataProvider'=>$dataProvider,
			'columns'=>array(
					array(
						'header'=>'Tanggal',
						'name'=>'idatetime',
						
					),
					array(
						'header'=>'No Faktur',
						'name'=>'id',
					),
					array(
						'header'=>'Nama Barang',
						'name'=>'nmkategori',
					),
					
					array(
						'header'=>'Jual',
						'name'=>'qty',
					),
					array(
						'header'=>'Harga Beli',
						'name'=>'hargabeli',
						'type'=>'number'
					),			
					
		),
		));
	};
	?>
	
<h2>
<?php 
	$mytotal = 0;
	foreach($data as $dt) {
		$mytotal += $dt['qty'] * $dt['hargabeli'];
	};
	
	echo "Total ".number_format($mytotal); 
?>
</h2>
