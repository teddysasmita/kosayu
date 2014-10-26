<?php
//============================================================+
// File name   : example_011.php
// Begin       : 2008-03-04
// Last Update : 2010-08-08
//
// Description : Example 011 for TCPDF class
//               Colored Table
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               Manor Coach House, Church Hill
//               Aldershot, Hants, GU12 4RQ
//               UK
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Colored Table
 * @author Nicola Asuni
 * @since 2008-03-04
 */

require_once('config/lang/eng.php');
//require_once('tcpdf.php');

// extend TCPF with custom functions
class MYPDF extends TCPDF {

	private $masterData;
	private $detailData;
	
	// Load table data from file
	public function LoadData($id) {
		// Read file lines
		$sqlheader="select * from stockentries where id='$id'";
		$sqldetail="select * from detailstockentries where id='$id' order by iditem";
		$this->masterData=Yii::app()->db->createCommand($sqlheader)->queryRow();
		$this->detailData=Yii::app()->db->createCommand($sqldetail)->queryAll();
	}

	// Colored table
	public function ColoredTable() {
		// Colors, line width and bold font
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B');
		
		// Header
		$w = array(10,110, 60);
		$headerData=array('No.', 'Nama Barang', 'Nomor Serial');
		$num_headers = count($headerData);
		for($i = 0; $i < $num_headers; ++$i) {
			$this->Cell($w[$i], 7, $headerData[$i], 1, 0, 'C', 1);
		}
		
		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = 0;
		$counter=1;
		foreach($this->detailData as $row) {
			$this->Cell($w[0], 6, $counter, 'LR', 0, 'C', $fill);
			$this->Cell($w[1], 6, lookup::ItemNameFromItemID($row['iditem']), 'LR', 0, 'L', $fill);
			$this->Cell($w[2], 6, $row['serialnum'], 'LR', 0, 'L', $fill);
			$this->Ln();
			//$fill=!$fill;
			$counter++;
		}
		$this->Cell(array_sum($w), 0, '', 'T');
	}
	
	public function master()
	{
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B');
		$this->SetFontSize(20);
		$this->SetTopMargin(10);
		
		$this->Cell(180, 20, 'Bukti Terima Barang dari Pemasok', 0, 1, 'C');
	
		$this->setFontSize(12);
		//print_r($this->masterData);
		//die();
		$this->Cell(30, 7, 'Nomor Urut', 'LT', 0, 'C', true);
		$this->Cell(70, 7, $this->masterData['regnum'], 'LT');
		$this->Cell(30, 7, 'Tanggal', 'LRT', 0, 'C', true);
		$this->Cell(50, 7, $this->masterData['idatetime'], 'LRT');
		$this->Ln();
		$this->SetFillColor(224, 235, 255);
		$this->Cell(30, 7, 'Pemasok', 'LT', 0, 'C', true);
		$this->SetFillColor(252, 186,243);
		$this->Cell(70, 7, 
			lookup::SupplierNameFromSupplierID($this->masterData['idsupplier']), 
				'LT', 0, 'L', true);
		$this->SetFillColor(224, 235, 255);
		$this->Cell(30, 7, 'Nomor PO', 'LT', 0, 'C', true);
		$this->SetFillColor(252, 186,243);
		$this->Cell(50, 7, 
			lookup::PurchasesOrderNumFromID($this->masterData['idpurchaseorder']), 
				'LRT', 0, 'L', true	);
		$this->Ln();
		$this->SetFillColor(224, 235, 255);
		$this->Cell(30, 7, 'Gudang', 'LT', 0, 'C', true);
		$this->Cell(70, 7, 
			lookup::WarehouseNameFromWarehouseID($this->masterData['idwarehouse']),'LRTB');	
		$this->SetFillColor(224, 235, 255);
		$this->Cell(30, 7, 'Kendaraan', 'LRBT', 0, 'C', true);
		$this->Cell(50, 7, $this->masterData['vehicleinfo'],'LRTB');
		$this->Ln();
		$this->SetFillColor(224, 235, 255);
		$this->Cell(30, 7, 'PIC', 'LRBT', 0, 'C', true);
		$this->Cell(70, 7, $this->masterData['pic'],'LRTB');
		$this->Ln();
		$this->SetFillColor(224, 235, 255);
		$this->Cell(30, 7, 'Catatan', 'LRBT', 0, 'C', true);
		$this->ln();
		$this->Cell(180, 21, $this->masterData['remark'],'LRTB');
		
		$this->Ln(30);
		
	} 
	
	
}



// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(lookup::UserNameFromUserID(Yii::app()->user->id));
$pdf->SetTitle('Laporan Penerimaan Barang');
$pdf->SetSubject('LPB');
$pdf->SetKeywords('Penerimaan Barang berdasarkan PO');

// set default header data
$pdf->setHeaderData(false, 0, 'Gunung Sari Intan', 
	'Laporan Penerimaan Barang', 'Testing');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 12);

// add a page
$pdf->AddPage();

$pdf->LoadData($model->id);

$pdf->master();
// print colored table
$pdf->ColoredTable();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('BTBP'.idmaker::getDateTime().'.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
?>

