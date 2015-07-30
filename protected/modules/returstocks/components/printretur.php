<?php


//require_once('tcpdf.php');

// extend TCPF with custom functions


class MYPDF extends TCPDF {

	private $data;
	private $detaildata;
	private $headernames;
	private $headerwidths;
	private $detaildata2;
	private $headernames2;
	private $headerwidths2;
	
	public $pageorientation;
	public $pagesize;
	
	// Load table data from file
	public function LoadData($data, array $detaildata) {
		// Read file lines
		$this->data = $data;
		$this->detaildata = $detaildata;
		$this->headernames = array('No', 'Nama Barang', 'Jmlh','H Beli', 'Total HB');
		$this->headerwidths = array(10, 93, 12, 35, 45);
	}
	
	public function LoadData2(array $detaildata2) {
		// Read file lines
		$this->detaildata2 = $detaildata2;
		$this->headernames2 = array('No', 'Nama Barang', 'Nomor Seri','Alasan');
		$this->headerwidths2 = array(10, 90, 30, 65);
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
		$total=0;
		$iditem='';
		$this->SetXY(1, 32);
		
		$this->SetFont('Courier', 'B');
		
		for($i = 0; $i < count($this->headernames); ++$i) {
			$this->Cell($this->headerwidths[$i], 7, $this->headernames[$i], 1, 0, 'C');
		}
		$this->ln();
		/*
		if (count($this->detaildata) <= 12)
			$maxrows = 12;
		else
			$maxrows = count($this->detaildata);		
		for($i=0;$i<$maxrows;$i++) {
		*/
		for ($i=0; $i<count($this->detaildata); $i++) {
			//if ($i<count($this->detaildata)) {
				$row=$this->detaildata[$i];
				$counter+=1;
				
				$ih = $this->getStringHeight($this->headerwidths[1],lookup::ItemNameFromItemID($row['iditem']),
					false, true, 2);
				$this->Cell($this->headerwidths[0], $ih, $counter, 'LR', 0, 'C', $fill);
				$this->MultiCell($this->headerwidths[1], 0, lookup::ItemNameFromItemID($row['iditem']), 'LR', 'L', 
						false, 0);
				// 0, 0, true, 0, false, true, 0, 'T', false);
				$this->Cell($this->headerwidths[2], $ih, $row['qty'], 'LR', 0, 'R', $fill);
				$this->Cell($this->headerwidths[3], $ih, number_format($row['buyprice']), 'LR', 0, 'R', $fill);
				$total=$total + $row['buyprice']*$row['qty'];
				$this->Cell($this->headerwidths[4], $ih, number_format($row['buyprice']*$row['qty']), 'LR', 0, 'R', $fill);
				//$this->MultiCell($this->headerwidths[5], 0, $row['remark'], 'LR', 'L', false, 0);
				//$this->Cell($this->headerwidths[5], $ih, $row['remark'], 'LR', 0, 'L', $fill);
				$this->ln($ih);
			/*} else {
				$this->Cell($this->headerwidths[0], 6, ' ', 'LR', 0, 'C', $fill);
				$this->Cell($this->headerwidths[1], 6, ' ', 'LR', 0, 'L', $fill);
				$this->Cell($this->headerwidths[2], 6, ' ', 'LR', 0, 'R', $fill);
				$this->Cell($this->headerwidths[3], 6, ' ', 'LR', 0, 'R', $fill);
				$this->Cell($this->headerwidths[4], 6, ' ', 'LR', 1, 'R', $fill);
				//$this->Cell($this->headerwidths[5], 6, ' ', 'LR', 1, 'L', $fill);
				//$this->ln();
			}*/
			//if (($i > 0) && ($i % 11 == 0))
				//$this->checkPageBreak(6, '');
				//$this->Cell(array_sum($this->headerwidths), 0, '', 'T', 1);
		}
		//$this->Cell(array_sum($this->headerwidths), 1, '', 'T', 1);
		$this->Cell(165, 6, 'Total Harga Beli', 'LRTB', 0, 'R');
		$this->Cell(30, 6, number_format($total), 'LRTB', 1, 'R');
		if ($this->data['remark'] <> '')
			$this->MultiCell(195, 0, $this->data['remark'], 'LRBT', 'L', false, 0);
		
		//$this->Cell(array_sum($this->headerwidths), 0, '', 'T');
	}
	
	// Colored table
	public function ColoredTable2() {
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
		$total=0;
		$iditem='';
		$this->SetXY(1, 32);
		
		$this->SetFont('Courier', 'B');
		
		for($i = 0; $i < count($this->headernames2); ++$i) {
			$this->Cell($this->headerwidths2[$i], 7, $this->headernames2[$i], 1, 0, 'C');
		}
		$this->ln();
		/*
			if (count($this->detaildata) <= 12)
			$maxrows = 12;
		else
			$maxrows = count($this->detaildata);
		for($i=0;$i<$maxrows;$i++) {
		*/
		for ($i=0; $i<count($this->detaildata2); $i++) {
			//if ($i<count($this->detaildata)) {
			$row=$this->detaildata2[$i];
			$counter+=1;
			
			$this->SetFontSize(10);
			$ih = $this->getStringHeight($this->headerwidths2[1],lookup::ItemNameFromItemID($row['iditem']),
					false, true, 2);
			$this->SetFontSize(8);
			$ih2 = $this->getStringHeight($this->headerwidths2[1],lookup::ItemNameFromItemID($row['iditem']),
					false, true, 2);
			if ($ih2 > $ih) 
				$ih = $ih2;
			$this->SetFontSize(10);
			$this->Cell($this->headerwidths2[0], $ih, $counter, 'LR', 0, 'C', $fill);
			$this->MultiCell($this->headerwidths2[1], 0, lookup::ItemNameFromItemID($row['iditem']), 'LR', 'L',
					false, 0);
			// 0, 0, true, 0, false, true, 0, 'T', false);
			$this->Cell($this->headerwidths2[2], $ih, $row['serialnum'], 'LR', 0, 'L', $fill);
			$this->SetFontSize(8);
			$this->Cell($this->headerwidths2[3], $ih, $row['remark'], 'LR', 0, 'L', $fill);
			$this->SetFontSize(10);
			//$this->MultiCell($this->headerwidths[5], 0, $row['remark'], 'LR', 'L', false, 0);
			//$this->Cell($this->headerwidths[5], $ih, $row['remark'], 'LR', 0, 'L', $fill);
			$this->ln($ih);
			/*} else {
			 $this->Cell($this->headerwidths[0], 6, ' ', 'LR', 0, 'C', $fill);
			$this->Cell($this->headerwidths[1], 6, ' ', 'LR', 0, 'L', $fill);
			$this->Cell($this->headerwidths[2], 6, ' ', 'LR', 0, 'R', $fill);
			$this->Cell($this->headerwidths[3], 6, ' ', 'LR', 0, 'R', $fill);
			$this->Cell($this->headerwidths[4], 6, ' ', 'LR', 1, 'R', $fill);
			//$this->Cell($this->headerwidths[5], 6, ' ', 'LR', 1, 'L', $fill);
			//$this->ln();
			}*/
			//if (($i > 0) && ($i % 11 == 0))
			//$this->checkPageBreak(6, '');
			//$this->Cell(array_sum($this->headerwidths), 0, '', 'T', 1);
		}
		$this->Cell(array_sum($this->headerwidths2), 1, '', 'T', 1);
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
		$this->SetFont('Courier', 'B');
		$this->SetFontSize(10);
		$this->setXY(1, 110);
		$this->Cell(43, 15, 'Admin', 'LTRB', 0, 'C', false,'', 0, false, 'T', 'T');
		$this->Cell(43, 15, 'Gudang', 'LTRB', 0, 'C', false,'', 0, false, 'T', 'T');
		$this->Cell(43, 15, 'Pemeriksa', 'LTRB', 0, 'C', false,'', 0, false, 'T', 'T');
		$this->Cell(43, 15, 'Penerima', 'LTRB', 0, 'C', false,'', 0, false, 'T', 'T');
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
		$this->SetFont('Courier', 'B');
		$this->setXY(100, 10);
		$this->Cell(96, 10, 'Retur Barang', 'LTR', 1, 'C');
		$this->SetFontSize(10);
		$this->setXY(100, 20);
		$this->Cell(10, 5, 'Tgl', 'LT', 0, 'C');
		$this->Cell(43, 5, $this->data->idatetime, 'LTR', 0, 'C');
		$this->SetFont('Courier', 'B');
		//$this->setXY(100, 27);
		$this->Cell(19, 5, 'No Retur', 'LTR', 0, 'C');
		$this->Cell(24, 5, $this->data->regnum, 'LTR', 1, 'C');
		
		$this->SetFont('Courier', 'B');
		$this->Cell(19, 5, 'Pengirim', 'LTR', 0,'C');
		$this->Cell(176, 5, lookup::SupplierNameFromSupplierID($this->data->idsupplier), 'LTR', 1);
		//$this->Cell(195, 15, $this->data->remark, 'LTRB', 1);
		
		//$this->ln();
		/*$this->setFontSize(12);
		$this->SetFont('Courier', 'B');
		
		for($i = 0; $i < count($this->headernames); ++$i) {
			$this->Cell($this->headerwidths[$i], 7, $this->headernames[$i], 1, 0, 'C');
		}*/
		/*$this->Cell(15, 7, 'No', 'LTRB', 0, 'C');
		$this->Cell(160, 7, 'Nama Barang', 'LTRB', 0, 'C');
		$this->Cell(20, 7, 'Jumlah', 'LTRB', 0 , 'C');*/
		
		
	} 	
}

function execute($model, $detailmodel, $detailmodel2) {

	// create new PDF document
	
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(215, 140), true, 'UTF-8', false);
	$pdf->pagesize = array(215, 140);
	$pdf->pageorientation = 'L';
	$pdf->setPageOrientation($pdf->pageorientation, TRUE);	
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor(lookup::UserNameFromUserID(Yii::app()->user->id));
	$pdf->SetTitle('Laporan Penerimaan Barang');
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
	$pdf->SetMargins(1, 32, PDF_MARGIN_RIGHT);
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
	$pdf->SetFont('Courier', '', 12);
	
	// add a page
	$pdf->LoadData($model, $detailmodel);
	$pdf->LoadData2($detailmodel2);
	
	$pdf->AddPage($pdf->pageorientation, $pdf->pagesize);
	//$pdf->AddPage();
	
	$pdf->ColoredTable();
	
	$pdf->AddPage($pdf->pageorientation, $pdf->pagesize);
	
	$pdf->ColoredTable2();
	
	//$pdf->master();
	// print colored table
	
	// ---------------------------------------------------------
	
	//Close and output PDF document
	$pdf->Output('RETURLPB-'.$model->regnum.'.pdf', 'D');
}
//============================================================+
// END OF FILE                                                
//============================================================+
?>

