<?php

require_once('config/lang/eng.php');
//require_once('tcpdf.php');

// extend TCPF with custom functions


class MYPDF extends TCPDF {

	private $data;
	private $detaildata;
	private $headernames;
	private $headerwidths;
	
	public $pageorientation;
	public $pagesize;
	
	// Load table data from file
	public function LoadData($data, array $detaildata) {
		// Read file lines
		$this->data = $data;
		$this->detaildata = $detaildata;
		$this->headernames = array('No', 'Nama Barang', 'Jmlh','Harga Net', 'Operasi');
		$this->headerwidths = array(10, 120, 15, 25, 25);
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
		
		// Data
		$fill = 0;
		$counter=0;
		$iditem='';
		$this->SetXY(1, 48);
		/*if (count($this->detaildata) <= 11)
			$maxrows = 11;
		else
			$maxrows = count($this->detaildata);		
		for($i=0;$i<$maxrows;$i++) {
			if ($i<count($this->detaildata)) {
		*/
		for($i=0; $i<count($this->detaildata); $i++) {	
			$row=$this->detaildata[$i];
				$counter+=1;
				$ih = $this->getStringHeight($this->headerwidths[1],lookup::ItemNameFromItemID($row['iditem']),
					false, true, 2);
				$this->Cell($this->headerwidths[0], 6, $counter, 'LR', 0, 'C', $fill);
				$this->MultiCell($this->headerwidths[1], 0, 
					lookup::ItemNameFromItemID($row['iditem']), 
					'LR', 'L', false, 0);
				//$this->Cell($this->headerwidths[1], 6, lookup::ItemNameFromItemID($row['iditem']), 'LR', 0, 'L', $fill);
				$this->Cell($this->headerwidths[2], 6, $row['qty'], 'LR', 0, 'R', $fill);
				$this->Cell($this->headerwidths[3], 6, number_format($row['price']), 'LR', 0, 'R', $fill);
				$this->Cell($this->headerwidths[4], 6, lookup::SalesreplaceNameFromCode($row['deleted']), 'LR');
				if ($row['deleted'] == '1') {
					$this->ln($ih);
					$ih = $this->getStringHeight($this->headerwidths[1],lookup::ItemNameFromItemID($row['iditem']),
						false, true, 2);
					$this->Cell($this->headerwidths[0], 6, '', 'LR', 0, 'C', $fill);
					$this->MultiCell($this->headerwidths[1], 0, 
						lookup::ItemNameFromItemID($row['iditemnew']), 
						'LR', 'L', false, 0);
					//$this->Cell($this->headerwidths[1], 6, lookup::ItemNameFromItemID($row['iditem']), 'LR', 0, 'L', $fill);
					$this->Cell($this->headerwidths[2], 6, $row['qtynew'], 'LR', 0, 'R', $fill);
					$this->Cell($this->headerwidths[3], 6, number_format($row['pricenew']-$row['discountnew']), 'LR', 0, 'R', $fill);
					$this->Cell($this->headerwidths[4], 6, '', 'LR', 1, 'R', $fill);
					//$counter+=1;	
				} else {
					$this->ln();
				}
				//$this->Cell($this->headerwidths[4], 6, number_format($row['discount']), 'LR', 1, 'R', $fill);
				//$this->Cell($this->headerwidths[5], 6, $row['remark'], 'LR', 1, 'L', $fill);
			/*} else {
				$this->Cell($this->headerwidths[0], 6, ' ', 'LR', 0, 'C', $fill);
				$this->Cell($this->headerwidths[1], 6, ' ', 'LR', 0, 'L', $fill);
				$this->Cell($this->headerwidths[2], 6, ' ', 'LR', 0, 'R', $fill);
				$this->Cell($this->headerwidths[3], 6, ' ', 'LR', 0, 'R', $fill);
				$this->Cell($this->headerwidths[4], 6, ' ', 'LR', 1, 'L', $fill);
				//$this->Cell($this->headerwidths[5], 6, ' ', 'LR', 1, 'L', $fill);
				//$this->ln();
			}*/
			//if (($i > 0) && ($i % 10 == 0))
				//$this->checkPageBreak(6, '');
				
		}
		//$this->Cell(array_sum($this->headerwidths), 0, '', 'T', 1);
		$this->Cell(130, 6, 'Selisih', 'LRBT', 0, 'R');
		$this->Cell(65, 6, number_format($this->data->totaldiff), 'RBT', 0, 'R');
		//$this->Cell(array_sum($this->headerwidths), 0, '', 'T');
	}
	
	public function header()
	{
		$this->master();
	}
	
	public function footer()
	{
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('Helvetica', 'B');
		$this->SetFontSize(10);
		$this->setXY(1, 115);
		$this->Cell(43, 15, 'CS', 'LTRB', 0, 'C', false,'', 0, false, 'T', 'T');
		$this->Cell(43, 15, 'Pembeli', 'LTRB', 0, 'C', false,'', 0, false, 'T', 'T');
		$this->Cell(43, 15, 'Manager', 'LTRB', 0, 'C', false,'', 0, false, 'T', 'T');
		$this->Cell(43, 15, '', 'LTRB', 0, 'C', false,'', 0, false, 'T', 'T');
		$this->Cell(23, 5, 'Halaman', 'LTR', 1, 'C', false,'', 0, false, 'T', 'T');
		$this->setX(173);
		$this->Cell(23, 5, $this->PageNo().' dari ', 'LR', 1, 'C', false,'', 0, false, 'T', 'T');
		$this->Cell(43, 5, lookup::UserNameFromUserID($this->data->userlog), 'LRB', 0, 'C', false,'', 0, false, 'T', 'T');
		$this->setX(173);
		$this->Cell(23, 5, 'total '.trim($this->getAliasNbPages()), 'LRB', 1, 'C', false,'', 0, false, 'T', 'T');
	}
	
	public function master()
	{
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B');
		$this->SetCellPadding(0.8);
	
		$this->SetFont('', 'IB');
		$this->setFontSize(20);
		
		$this->setXY(10, 5);
		$this->Cell(90, 20, 'Gunung Sari Intan');
		
		
		$this->setFontSize(20);
		$this->SetFont('', 'B');
		$this->setXY(91, 10);
		$this->Cell(105, 10, 'Perubahan Faktur', 'LTR', 1, 'C');
		$this->SetFontSize(10);
		$this->setXY(91, 20);
		$this->Cell(22, 5, 'Tanggal', 'LT', 0, 'C');
		$this->Cell(35, 5, $this->data->idatetime, 'LTR', 0, 'C');
		$this->SetFont('Helvetica', 'B');
		//$this->setXY(100, 27);
		$this->Cell(20, 5, 'No. Faktur', 'LTR', 0, 'C');
		$this->Cell(28, 5, $this->data->invnum, 'LTR', 1);
		
		$this->setXY(91, 26);
		$this->Cell(22, 5, 'No. Batal', 'LT', 0, 'C');
		$this->Cell(83, 5, $this->data->regnum, 'LTR', 1, 'C');
		
		$this->SetFont('Helvetica', 'B');
		$this->Cell(20, 10, 'Alasan', 'LTRB', 0,'C');
		$this->Cell(175, 10, $this->data->reason, 'LTRB', '1', 'L', false, '', 0, false, 'T', 'T' );
		/*
		$this->SetFont('Helvetica', 'B');
		$this->Cell(15, 5, 'No. SJ ', 'LTR', 0,'C');
		$this->Cell(32, 5, $this->data->sjnum, 'LTR');
		$this->Cell(20, 5, 'No. PO ', 'LTR', 0,'C');
		$this->Cell(28, 5, $this->data->ponum, 'LTR', 1, 'C');
		*/
		//$this->Cell(195, 15, $this->data->remark, 'LTRB', 1);
		
		//$this->ln();
		$this->setFontSize(11);
		$this->SetFont('Helvetica', 'B');
		
		for($i = 0; $i < count($this->headernames); ++$i) {
			$this->Cell($this->headerwidths[$i], 7, $this->headernames[$i], 1, 0, 'C');
		}
		/*$this->Cell(15, 7, 'No', 'LTRB', 0, 'C');
		$this->Cell(160, 7, 'Nama Barang', 'LTRB', 0, 'C');
		$this->Cell(20, 7, 'Jumlah', 'LTRB', 0 , 'C');*/
		
		
	} 	
}

function execute($model, $detailmodel) {

	// create new PDF document
	
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(215, 140), true, 'UTF-8', false);
	$pdf->pagesize = array(215, 140);
	$pdf->pageorientation = 'L';
	$pdf->setPageOrientation($pdf->pageorientation, TRUE);	
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor(lookup::UserNameFromUserID(Yii::app()->user->id));
	$pdf->SetTitle('Laporan Perubahan Penjualan');
	$pdf->SetSubject('LPB');
	$pdf->SetKeywords('LPB');
	
	//$pdf->setPrintHeader(false);
	//$pdf->setPrintFooter(false);
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(1, 77, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(0);
	$pdf->SetFooterMargin(0);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, 20);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	//set some language-dependent strings
	//$pdf->setLanguageArray($l);
	
	// ---------------------------------------------------------
	
	// set font
	$pdf->SetFont('helvetica', '', 12);
	
	// add a page
	$pdf->LoadData($model, $detailmodel);
	
	$pdf->AddPage($pdf->pageorientation, $pdf->pagesize);
	//$pdf->AddPage();
	
	$pdf->ColoredTable();
	//$pdf->master();
	// print colored table
	
	// ---------------------------------------------------------
	
	//Close and output PDF document
	$pdf->Output('FG-'.$model->regnum.'.pdf', 'D');
}
//============================================================+
// END OF FILE                                                
//============================================================+
?>

