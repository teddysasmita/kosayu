<?php
/* @var $this SalespostransfersController */
/* @var $model Salespostransfers */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
	'Laporan Pendapatan Kasir'=>array('salesposreport/create'),
	'Lihat Data'
);

$this->menu=array(
	
);
?>

<h1>Laporan Pendapatan Kasir</h1>

<?php 
	
	$dataProvider=new CArrayDataProvider($data,array(
		'totalItemCount'=>count($data),
		'pagination'=>array(
				'pageSize'=>20,
			),
	));
	$this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'columns'=>array(
				array(
						'header'=>'Tanggal',
						'name'=>'idate',
				),
				array(
						'header'=>'Kasir',
						'name'=>'idcashier',
						'value'=>"lookup::UserNameFromUserID(\$data['idcashier'])",
				),
				array(
						'header'=>'Metode',
						'name'=>'method',
						'value'=>"lookup::getMethod(\$data['method'])",
				),
				array(
						'header'=>'Kurs',
						'name'=>'idcurr',
						'value'=>"lookup::CurrSymbolFromID(\$data['idcurr'])",
				),
				array(
						'header'=>'Nilai Tukar',
						'name'=>'idrate',
						'value'=>"lookup::CurrRateFromID(\$data['idrate'])",
				),
				array(
						'header'=>'Jumlah',
						'name'=>'total',
						'type'=>'number',
				),
		),
));
	
?>

<?php
	$total = array();
	$temp['id'] = 1;
	$temp['tunai'] = 0;
	$temp['kartukredit'] = 0;
	$temp['kartudebit'] = 0;
	$temp['voucher'] = 0;
	$temp['retur'] = 0;
	$total[] = $temp;
	
	foreach($data as $d) {
		if ($d['method'] == 'KK') {
			$total[0]['kartukredit'] += $d['total'];
		} else if ($d['method'] == 'KD') {
			$total[0]['kartudebit'] += $d['total'];
		} else if ($d['method'] == 'V') {
			$total[0]['voucher'] += $d['total'];
		} else if ($d['method'] == 'R') {
			$total[0]['retur'] += $d['total'];
		} else if ($d['method'] == 'C') {
			$rate = lookup::CurrRateFromID($d['idrate']);
			if ($rate == '-')
				$rate = 1;
			$total[0]['tunai'] += $d['total'] * $rate;
		} 
	}
	
	$dataProvider2=new CArrayDataProvider($total,array(
			'totalItemCount'=>count($total),
	));
	
	$this->widget('zii.widgets.grid.CGridView', array(
			'dataProvider'=>$dataProvider2,
			'columns'=>array(
					array(
							'header'=>'Total Tunai',
							'name'=>'tunai',
							'type'=>'number',
					),
					array(
							'header'=>'Total Kartu Kredit',
							'name'=>'kartukredit',
							'type'=>'number',
					),
					array(
							'header'=>'Total Kartu Debit',
							'name'=>'kartudebit',
							'type'=>'number',
					),
					array(
							'header'=>'Total Voucher',
							'name'=>'voucher',
							'type'=>'number',
					),
					array(
							'header'=>'Total Retur',
							'name'=>'retur',
							'type'=>'number',
					),
			),
	));
	
	$grandtotal = $total[0]['tunai'] + $total[0]['kartukredit'] + $total[0]['kartudebit'] +
		$total[0]['voucher'] + $total[0]['retur'];
	
	echo CHtml::tag('h1', false, number_format($grandtotal));
?>
