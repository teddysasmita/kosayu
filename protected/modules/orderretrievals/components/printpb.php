<?php

require_once('config/lang/eng.php');
//require_once('tcpdf.php');

// extend TCPF with custom functions


class MYPDF extends TCPDF {

	private $data;
	private $detaildata;
	private $receivable;
	private $headernames;
	private $headerwidths;
	
	public $pageorientation;
	public $pagesize;
	
	// Load table data from file
	public function LoadData($data, array $detaildata, $receivable) {
		// Read file lines
		$this->data = $data;
		$this->detaildata = $detaildata;
		$this->receivable = $receivable;
		$this->headernames = array('No', 'Nama Barang', 'Jmlh', 'Gudang', 'Keterangan');
		$this->headerwidths = array(10, 120, 15, 20, 30);
	}

	// Colored table
	public function ColoredTable() {
		// Colors, line width and bold font
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('Courier', 'B');
		$this->SetFontSize(10);
		
		// Data
		$fill = 0;
		$counter=0;
		$iditem='';
		$this->SetXY(1, 45);
		if (count($this->detaildata) <= 11)
			$maxrows = 11;
		else
			$maxrows = count($this->detaildata);		
		for($i=0;$i<$maxrows;$i++) {
			if ($i<count($this->detaildata)) {
				$row=$this->detaildata[$i];
				$counter+=1;
				$this->Cell($this->headerwidths[0], 6, $counter, 'LR', 0, 'C', $fill);
				$this->Cell($this->headerwidths[1], 6, lookup::ItemNameFromItemID($row['iditem']), 
						'LR', 0, 'L', $fill);
				$this->Cell($this->headerwidths[2], 6, $row['qty'], 'LR', 0, 'R', $fill);
				$this->Cell($this->headerwidths[3], 6, lookup::WarehouseNameFromWarehouseID($row['idwarehouse']), 
						'LR', 0, 'R', $fill);
				$this->Cell($this->headerwidths[4], 6, '', 'LR', 1, 'R', $fill);
			} else {
				$this->Cell($this->headerwidths[0], 6, ' ', 'LR', 0, 'C', $fill);
				$this->Cell($this->headerwidths[1], 6, ' ', 'LR', 0, 'L', $fill);
				$this->Cell($this->headerwidths[2], 6, ' ', 'LR', 0, 'R', $fill);
				$this->Cell($this->headerwidths[3], 6, ' ', 'LR', 0, 'R', $fill);
				$this->Cell($this->headerwidths[4], 6, ' ', 'LR', 1, 'R', $fill);
				//$this->ln();
			}
			if (($i > 0) && ($i % 10 == 0))
				//$this->checkPageBreak(6, '');
				$this->Cell(array_sum($this->headerwidths), 0, '', 'T', 1);
		}
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
		$this->setXY(1, 115);
		$this->Cell(43, 15, 'Supir', 'LTRB', 0, 'C', false,'', 0, false, 'T', 'T');
		$this->Cell(43, 15, 'CS', 'LTRB', 0, 'C', false,'', 0, false, 'T', 'T');
		$this->Cell(43, 15, 'Pemeriksa', 'LTRB', 0, 'C', false,'', 0, false, 'T', 'T');
		$this->Cell(43, 15, 'Penerima', 'LTRB', 0, 'C', false,'', 0, false, 'T', 'T');
		$this->Cell(23, 5, 'Halaman', 'LTR', 1, 'C', false,'', 0, false, 'T', 'T');
		$this->setX(173);
		$this->Cell(23, 5, $this->PageNo().' dari ', 'LR', 1, 'C', false,'', 0, false, 'T', 'T');
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
	
		$this->setFontSize(20);
		$this->setXY(91, 10);
		$this->Cell(105, 10, 'Ambil Barang', 'LTR', 1, 'C');
		$this->SetFontSize(10);
		$this->SetFont('Courier', 'B');
		$this->setXY(91, 20);
		$this->Cell(20, 5, 'Tgl', 'LT', 0, 'C');
		$this->Cell(45, 5, $this->data->idatetime, 'LTR', 0, 'C');
		$this->Cell(15, 5, 'No SJ', 'LTR', 0, 'C');
		$this->Cell(25, 5, $this->data->regnum, 'LTR', 1, 'C');
		
		$this->setXY(91, 26);
		$this->Cell(20, 5, 'No Faktur', 'LT', 0, 'C');
		$this->Cell(45, 5, $this->data->invnum, 'LTR', 0, 'C');
		$this->Cell(15, 5, 'Status', 'LTR', 0, 'C');
		if ($this->receivable > 0)
			$this->Cell(25, 5, 'Piutang', 'LTR', 1, 'C');
		else
			$this->Cell(25, 5, 'Lunas', 'LTR', 1, 'C');
		//$this->setXY(100, 27);
		
		
		
		$this->SetFont('Courier', 'B');
		$this->Cell(35, 5, 'Nama Penerima', 'LTR', 0,'C');
		$this->Cell(80, 5, $this->data->receivername, 'LTR');
		$this->SetFont('Courier', 'B');
		$this->Cell(30, 5, 'Telp Penerima', 'LTR', 0,'C');
		$this->Cell(50, 5, $this->data->receiverphone, 'LTR', 1);
		
		//$this->ln(5);
		$this->setFontSize(12);
		$this->SetFont('Courier', 'B');
		
		for($i = 0; $i < count($this->headernames); ++$i) {
			$this->Cell($this->headerwidths[$i], 7, $this->headernames[$i], 1, 0, 'C');
		}
		/*$this->Cell(15, 7, 'No', 'LTRB', 0, 'C');
		$this->Cell(160, 7, 'Nama Barang', 'LTRB', 0, 'C');
		$this->Cell(20, 7, 'Jumlah', 'LTRB', 0 , 'C');*/
		
		
	} 	
}

function execute($model, $detailmodel, $receivable) {

	// create new PDF document
	
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(215, 140), true, 'UTF-8', false);
	$pdf->pagesize = array(215, 140);
	$pdf->pageorientation = 'L';
	$pdf->setPageOrientation($pdf->pageorientation, TRUE);	
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor(lookup::UserNameFromUserID(Yii::app()->user->id));
	$pdf->SetTitle('Surat Jalan Manual');
	$pdf->SetSubject('SJM');
	$pdf->SetKeywords('SJM');
	
	//$pdf->setPrintHeader(false);
	//$pdf->setPrintFooter(false);
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(1, 45, PDF_MARGIN_RIGHT);
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
	$pdf->LoadData($model, $detailmodel, $receivable);
	
	$pdf->AddPage($pdf->pageorientation, $pdf->pagesize);
	//$pdf->AddPage();
	
	$pdf->ColoredTable();
	//$pdf->master();
	// print colored table
	
	// ---------------------------------------------------------
	
	//Close and output PDF document
	$pdf->Output('PB'.idmaker::getDateTime().'.pdf', 'D');
}
//============================================================+
// END OF FILE                                                
//============================================================+
?>

