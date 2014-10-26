<?php

require_once('config/lang/eng.php');
//require_once('tcpdf.php');

// extend TCPF with custom functions


class MYPDF extends TCPDF {

	private $itemname;
	private $warehousecode;
	private $detaildata;
	
	// Load table data from file
	public function LoadData($itemname, $warehousecode, array $detaildata) {
		// Read file lines
		$this->itemname = $itemname;
		$this->warehousecode = $warehousecode;
		$this->detaildata = $detaildata;
	}

	// Colored table
	public function ColoredTable() {
		// Colors, line width and bold font
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B');
		$this->SetFontSize(10);
		
		$headernames = array('Tanggal', 'Keterangan', 'Masuk', 'Keluar', 'Total', 'Paraf');
		$headerwidths = array(35, 85, 15, 15, 20, 20);
		$headers = array_combine($headernames, $headerwidths);
		for($i = 0; $i < count($headernames); ++$i) {
			$this->Cell($headerwidths[$i], 7, $headernames[$i], 1, 0, 'C', 1);
		}
		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetFont('');
		$this->SetFontSize(9);
		
		// Data
		$fill = 0;
		$counter=0;
		$iditem='';
		for($i=0;$i<34;$i++) {
			if ($i<count($this->detaildata)) {
				$row=$this->detaildata[$i];
				$counter+=$row['qty'];
				$this->Cell($headerwidths[0], 6, $row['idatetime'], 'LRB', 0, 'C', $fill);
				$this->Cell($headerwidths[1], 6, $row['message'].'- '.$row['operationlabel'], 'LRB', 0, 'L', $fill);
				if ($row['qty']>0)
					$inqty=$row['qty'];
				else
					$inqty=' ';
				$this->Cell($headerwidths[2], 6, $inqty, 'LRB', 0, 'C', $fill);
				if ($row['qty']<0)
					$outqty= -($row['qty']);
				else
					$outqty=' ';
				$this->Cell($headerwidths[3], 6, $outqty, 'LRB', 0, 'C', $fill);
				$this->Cell($headerwidths[4], 6, $counter, 'LRB', 0, 'C', $fill);
				$this->Cell($headerwidths[5], 6, lookup::UserNameFromUserID($row['userlog']), 'LRB', 0, 'C', $fill);
				$this->ln();
			} else {
				$this->Cell($headerwidths[0], 6, ' ', 'LRB', 0, 'C', $fill);
				$this->Cell($headerwidths[1], 6, ' ', 'LRB', 0, 'L', $fill);
				$this->Cell($headerwidths[2], 6, ' ', 'LRB', 0, 'C', $fill);
				$this->Cell($headerwidths[3], 6, ' ', 'LRB', 0, 'C', $fill);
				$this->Cell($headerwidths[4], 6, ' ', 'LRB', 0, 'C', $fill);
				$this->Cell($headerwidths[5], 6, ' ', 'LRB', 0, 'C', $fill);
				$this->ln();
			}
		}
		
		$this->Cell(array_sum($headerwidths), 0, '', 'T');
	}
	
	public function master()
	{
		global $itemname;
		global $warehousecode;
				
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B');
		$this->SetFontSize(10);
		$this->Cell(190, 10, 'Dicetak: '.UserIdentity::getUserName(), 0, 1, 'R');
		$this->SetFontSize(20);
		$this->Cell(180, 20, 'Kartu Stok', 0, 1, 'C');
	
		$this->setFontSize(12);
		//print_r($this->masterData);
		//die();
		$this->Cell(30, 7, 'Nama Barang', 'LT', 0, 'C', true);
		$this->SetTextColor(16,94,139);
		$this->SetFillColor(255,236,77);
		$this->Cell(160, 7, $this->itemname, 'LRTB', 0, 'C', true);
		$this->Ln();
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->Cell(30, 7, 'Gudang', 'LRTB', 0, 'C', true);
		$this->SetTextColor(16,94,139);
		$this->SetFillColor(255,236,77);
		$this->Cell(50, 7, $this->warehousecode, 'LRTB', 0, 'C', true);
		/*
		$this->Ln();
		$this->SetFillColor(224, 235, 255);
		$this->Cell(30, 7, 'Catatan', 'LRBT', 0, 'C', true);
		$this->Cell(150, 14, $this->masterData['remark'],'LRTB');
		*/
		$this->Ln(10);
	} 
	
	
}


function execute($itemname, $warehousecode, $detaildata) 
{
	// create new PDF document
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor(lookup::UserNameFromUserID(Yii::app()->user->id));
	$pdf->SetTitle('Kartu Stok');
	$pdf->SetSubject($itemname);
	$pdf->SetKeywords($itemname);
	
	// set default header data
	$pdf->setHeaderData(false, 0, 'Gunung Sari Intan', 
		'Cetak Kartu Stok', 'Testing');
	
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
	//$pdf->setLanguageArray($l);
	
	// ---------------------------------------------------------
	
	// set font
	$pdf->SetFont('helvetica', '', 12);
	
	// add a page
	$pdf->AddPage();
	
	$pdf->LoadData($itemname, $warehousecode, $detaildata);
	
	$pdf->master();
	// print colored table
	$pdf->ColoredTable();
	
	// ---------------------------------------------------------
	
	//Close and output PDF document
	$pdf->Output('BTBP'.idmaker::getDateTime().'.pdf', 'I');
};
//============================================================+
// END OF FILE                                                
//============================================================+
?>

