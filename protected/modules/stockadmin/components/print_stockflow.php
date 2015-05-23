<?php

require_once('config/lang/eng.php');
//require_once('tcpdf.php');

// extend TCPF with custom functions


class MYPDF extends TCPDF {

	private $data;
	private $suppliercode;
	private $startdate;
	private $enddate;
	private $headernames;
	private $headerwidths;
	private $total = 0;
	
	public $left_margin = 10;
	public $right_margin = 10;
	
	// Load table data from file
	public function LoadData($data, $suppliercode, $startdate, $enddate) {
		// Read file lines
		$this->data = $data;
		$this->suppliercode = $suppliercode;
		$this->startdate = $startdate;
		$this->enddate = $enddate;
		$this->headernames = array(
				'Kode Batch', 'Nama Barang', 'Jml Awal', 'Jml Beli', 'Jml Jual', 'Jml Retur B', 'Jml Retur J', 'Jml Akhir'
		);
		$this->headerwidths = array(25, 65, 10, 15, 20, 20, 20, 20);
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
		$this->SetXY(10, 33);
		$totalqty = $totalrqty = $totalsold = $totaldisc = $totalcog = $totalqty = $totalgain = 0;	
		
		$this->setX(10);
		for ($i=0;$i<count($this->data);$i++) {
			$row=$this->data[$i];
			$counter+=1;
			
			$ih = 6;
			
			$this->checkPageBreak($ih);
			$this->Cell($this->headerwidths[0], $ih, $row['batchcode'], 'BLR', 0, 'C', $fill);
			$this->MultiCell($this->headerwidths[1], $ih, $row['name'], 
				'BR', 'L', false, 0,'','',true,0,false,true,0,'M');
			$this->Cell($this->headerwidths[2], $ih, number_format($row['startqty']), 'BR', 0, 'R', $fill);
			//$totalqty += $row['qty'];
			$this->Cell($this->headerwidths[3], $ih, number_format($row['receiveqty']), 'BR', 0, 'R', $fill);
			//$totalrqty += $row['rqty'];
			$this->Cell($this->headerwidths[4], $ih, number_format($row['soldqty']), 'BR', 0, 'R', $fill);
			//$totalsold += $row['totalsold'];
			$this->Cell($this->headerwidths[5], $ih, number_format($row['returqty']), 'BR', 0, 'R', $fill);
			//$totaldisc += $row['totaldisc'];
			$this->Cell($this->headerwidths[6], $ih, number_format($row['salereturqty']), 'BR', 0, 'R', $fill);
			//$totalcog += $row['totalcog'];
			$this->Cell($this->headerwidths[7], $ih, number_format($row['endqty']), 'BR', 1, 'R', $fill);
			//$totalgain += $row['totalgain'];
		} 
		/**$this->setX(10);
		$this->Cell($this->headerwidths[0] + $this->headerwidths[1], 5, 'Total', 'LTB', 0, 'R');
		$this->Cell($this->headerwidths[2], 5, number_format($totalqty), 'LTB', 0, 'R');
		$this->Cell($this->headerwidths[3], 5, number_format($totalrqty), 'LTB', 0, 'R');
		$this->Cell($this->headerwidths[4], 5, number_format($totalsold), 'LTB', 0, 'R');
		$this->Cell($this->headerwidths[5], 5, number_format($totaldisc), 'LTB', 0, 'R');
		$this->Cell($this->headerwidths[6], 5, number_format($totalcog), 'LTB', 0, 'R');
		$this->Cell($this->headerwidths[7], 5, number_format($totalgain), 'LTBR', 1, 'R');
		$this->Cell(array_sum($this->headerwidths), 0, '', 'T');**/
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
	
		$this->setFontSize(7);
		$this->setXY(170, 10);
		$this->Cell(20, 10, $this->getPage().' / '.$this->getAliasNbPages(), '', 1, 'C');
		$this->setFontSize(15);
		$this->setXY(10, 10);
		$this->Cell(195, 10, 'Laporan Aliran Stok', 'LTR', 1, 'C');
		$this->setXY(10, 20);
		$this->setFontSize(10);
		$this->Cell(50, 5, 'Kode Supplier : '. $this->suppliercode,'LTR', 0, 'L');
		$this->Cell(145, 5, 'Periode  : '. $this->startdate. ' s/d '.$this->enddate, 'LTR', 1, 'L');		
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

function execute($data, $suppliercode, $startdate, $enddate) {

	// create new PDF document
	
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor(lookup::UserNameFromUserID(Yii::app()->user->id));
	$pdf->SetTitle('Laporan Aliran Stok');
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
	$pdf->SetMargins($pdf->left_margin, 27, $pdf->right_margin);
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
	
	$pdf->LoadData($data, $suppliercode, $startdate, $enddate);
	//$pdf->AddPage(PDF_PAGE_ORIENTATION, 'A4');
	$pdf->AddPage();
	
	$pdf->ColoredTable();
	//$pdf->master();
	// print colored table
	
	// ---------------------------------------------------------
	
	//Close and output PDF document
	$pdf->Output('Laporan Aliran Stok -'.idmaker::getDateTime().'.pdf', 'D');
}
//============================================================+
// END OF FILE                                                
//============================================================+
?>

