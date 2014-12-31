<?php

require_once('config/lang/eng.php');
//require_once('tcpdf.php');

// extend TCPF with custom functions


class MYPDF extends TCPDF {

	private $data;
	private $headernames;
	private $headerwidths;
	private $total = 0;
	
	
	// Load table data from file
	private function LoadData($data) {
		// Read file lines
		$this->data = $data;
		$this->headernames = array('Tanggal', 'Nama Pemasok', 'Kode', 'Nama Barang', 'Jmlh', 
			'Harga@', 'Disc',' Total');
		$this->headerwidths = array(30, 30, 30, 30, 15, 20, 20, 20);
	}

	// Colored table
	public function ColoredTable() {
		// Colors, line width and bold font
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('Helvetica', 'B');
		$this->SetFontSize(8);
		
		// Data
		$fill = 0;
		$counter=0;
		$iditem='';
		$this->SetXY(10, 42);
			
		$this->setX(10);
			if ($i<count($this->data)) {
				$row=$this->data[$i];
				$counter+=1;
				
				$this->Cell($this->headerwidths[0], 6, substr($row['idatetime'],0,10), 0, 0, 'C', $fill);
				$this->Cell($this->headerwidths[1], 6, 
					lookup::SupplierNameFromSupplierID($row['idsupplier']), 0, 0, 'C', $fill);
				$this->Cell($this->headerwidths[2], 6, $row['batchcode'], 0, 0, 'C', $fill);
				$this->Cell($this->headerwidths[3], 6, 
					lookup::ItemNameFromItemID($row['iditem']), 0, 0, 'C', $fill);
				$this->Cell($this->headerwidths[4], 6, number_format($row['qty']), 'LR', 0, 'R', $fill);
				$this->Cell($this->headerwidths[5], 6, number_format($row['price']), 
						'LR', 0, 'R', $fill);
				$this->Cell($this->headerwidths[6], 6, number_format($row['discount']),
						'LR', 0, 'R', $fill);
				$total = $row['qty'] * ($row['price']-$row['discount']);
				$this->total += $total;
				$this->Cell($this->headerwidths[7], 6, number_format($total), 'LR', 1, 'R', $fill);
			} 
		$this->setX(10);
		$this->Cell(120, 5, 'Total', 'LTB', 0, 'R');
		$this->Cell(65, 5, number_format($this->total), 'LTBR', 1, 'R');
		//$this->Cell(array_sum($this->headerwidths), 0, '', 'T');
	}
	
	public function header()
	{
		$this->master();
	}
	
	public function footer()
	{
		/* 
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('Helvetica', 'B');
		$this->SetFontSize(10);
		$this->setXY(10, 115);
		
		$this->Cell(42, 15, 'Pembelian', 'LTRB', 0, 'C', false,'', 0, false, 'T', 'T');
		$this->Cell(40, 15, 'Pemeriksa', 'LTRB', 0, 'C', false,'', 0, false, 'T', 'T');
		$this->Cell(40, 15, 'Admin', 'LTRB', 0, 'C', false,'', 0, false, 'T', 'T');
		$this->Cell(40, 15, 'a.n Pemasok', 'LTRB', 0, 'C', false,'', 0, false, 'T', 'T');
		$this->Cell(23, 5, 'Halaman', 'LTR', 1, 'C', false,'', 0, false, 'T', 'T');
		$this->setX(172);
		$this->Cell(23, 5, $this->PageNo().' dari ', 'LR', 1, 'C', false,'', 0, false, 'T', 'T');
		$this->setX(172);
		$this->Cell(23, 5, 'total '.trim($this->getAliasNbPages()), 'LRB', 1, 'L', false,'', 0, false, 'T', 'T');
		*/
	}
	
	public function master()
	{
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B');
		$this->SetCellPadding(0.8);
	
		$this->setFontSize(15);
		$this->setXY(10, 10);
		$this->Cell(185, 10, 'Laporan Pembelian Putus', 'LTR', 1, 'C');
		$this->SetFontSize(10);		
		$this->setX(10);
		$this->SetFont('Helvetica', 'B');
		
		for($i = 0; $i < count($this->headernames); ++$i) {
			$this->Cell($this->headerwidths[$i], 7, $this->headernames[$i], 1, 0, 'C');
		}
		/*$this->Cell(15, 7, 'No', 'LTRB', 0, 'C');
		$this->Cell(160, 7, 'Nama Barang', 'LTRB', 0, 'C');
		$this->Cell(20, 7, 'Jumlah', 'LTRB', 0 , 'C');*/
		
		
	} 	
}

function execute($data) {

	// create new PDF document
	
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor(lookup::UserNameFromUserID(Yii::app()->user->id));
	$pdf->SetTitle('Laporan Pembelian Putus');
	$pdf->SetSubject('BP');
	$pdf->SetKeywords('BP');
	
	//$pdf->setPrintHeader(false);
	//$pdf->setPrintFooter(false);
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(0);
	$pdf->SetFooterMargin(0);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(2.8);
	
	//set some language-dependent strings
	//$pdf->setLanguageArray($l);
	
	// ---------------------------------------------------------
	
	// set font
	$pdf->SetFont('helvetica', '', 12);
	
	// add a page
	
	//$pdf->LoadData($data);
	print_r($data);
	die;
	//$pdf->AddPage(PDF_PAGE_ORIENTATION, 'A4');
	$pdf->AddPage();
	
	$pdf->ColoredTable();
	//$pdf->master();
	// print colored table
	
	// ---------------------------------------------------------
	
	//Close and output PDF document
	$pdf->Output('Laporan Beli Putus'.idmaker::getDateTime().'.pdf', 'D');
}
//============================================================+
// END OF FILE                                                
//============================================================+
?>

