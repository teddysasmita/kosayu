<?php

$detaildata = array();
$data = array();
$detaildata2 = array();

function execute( $idata, $idetaildata, $idetaildata2) 
{
	global $data;
	global $detaildata;
	global $detaildata2;
	
	$data = $idata;
	$detaildata = $idetaildata;
	$detaildata2 = $idetaildata2;
	
	$printer = new Print2Text();
	$printer->pageWidth = 40;
	$printer->printHLine();
	foreach($detaildata as $dm) {
		$printer->printText(1, 20, $dm['idatetime']);
		$printer->printText(1, 10, number_format($dm['amount']), 'R', 1);
	}
	$printer->printHLine();
	
	//header('Content-type: application/txt');
	//header('Content-Disposition: attachment; filename = "Bayar Komisi-'.$id.'.txt"');
	$printer->sendOutput();
}



?>