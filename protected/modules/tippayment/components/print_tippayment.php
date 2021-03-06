<?php


//require_once('tcpdf.php');

// extend TCPF with custom functions


class MYPDF extends TCPDF {

	private $data;
	private $detaildata;
	private $detaildata2;
	
	private $headernames;
	private $headerwidths;
	
	public $pageorientation;
	public $pagesize;
	public $leftmargin;
	
	// Load table data from file
	public function LoadData($data, array $detaildata, array $detaildata2) {
		// Read file lines
		$this->data = $data;
		$this->detaildata = $detaildata;
		$this->detaildata2 = $detaildata2;
		
		$this->headernames1 = array('Struk', 'Total', 'Disc', 'Waktu', 'Kasir' );
		$this->headerwidths1 = array(9, 16, 11, 37, 12);
		
		$this->headernames2 = array('Jenis Komisi', 'Jumlah' );
		$this->headerwidths2 = array(40, 40);
	}

	// Colored table
	public function ColoredTable() {
		// Colors, line width and bold font
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->setfontsize(10);

		$this->SetXY($this->leftmargin, 35);
		for($i = 0; $i < count($this->headernames1); ++$i) {
			$this->Cell($this->headerwidths1[$i], 5, $this->headernames1[$i], 'TB', 0, 'C');
		}
		$this->ln();
		// Data
		$fill = 0;
		$counter=0;
		$total=0;
		$iditem='';
		
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
			$this->setX($this->leftmargin);
			$ih = $this->getStringHeight($this->headerwidths1[1],$row['invoicenum'], false, true, 2);
			$this->Cell($this->headerwidths1[0], $ih, $row['invoicenum'], 0, 0, 'C');
			$this->Cell($this->headerwidths1[1], $ih, number_format($row['amount']), 0, 0, 'R');
			$this->Cell($this->headerwidths1[2], $ih, number_format($row['totaldiscount']), 0, 0, 'R');
			unset($cashierlog);
			$cashierlog = substr($row['cashierlog'], 2, strlen($row['cashierlog'])-2);
			$cashierlog = substr($cashierlog, 0, strlen($cashierlog)-3);
			$this->Cell($this->headerwidths1[3], $ih, $cashierlog, 0, 0, 'C');
			$cashiername = substr(lookup::UserNameFromUserID($row['idcashier']), 0, 7);
			$this->Cell($this->headerwidths1[4], $ih, $cashiername , 0, 0, 'C');
			$this->ln($ih);
			//$this->checkPageBreak($ih);
		}
		
		$this->ln(5);
		
		$this->setX($this->leftmargin);		
		
		for($i = 0; $i < count($this->headernames2); ++$i) {
			$this->Cell($this->headerwidths2[$i], 5, $this->headernames2[$i], 'TB', 0, 'C');
		}
		$this->Ln();
		
		for ($i=0; $i<count($this->detaildata2); $i++) {
			//if ($i<count($this->detaildata)) {
			$row=$this->detaildata2[$i];
			$counter+=1;
		
			$ih = $this->getStringHeight($this->headerwidths2[1],$row['amount'], false, true, 2);
			$this->setX($this->leftmargin);
			$this->Cell($this->headerwidths2[0], $ih, lookup::ItemTipGroupNameFromID($row['idtipgroup']), 0, 0, 'L');
			$this->Cell($this->headerwidths2[1], $ih, number_format($row['amount']), 0, 0, 'R');
			$this->ln($ih);
			//$this->checkPageBreak($ih);
		}
		$this->setX($this->leftmargin);
		$this->Cell(85,1,'','B',1);
		
		$this->SetFontSize(11);
		$this->setX($this->leftmargin);
		$this->Cell(45, 5, 'Total Nota:', 0, 0, 'R'); 
		$this->Cell(40, 5, number_format($this->data->totalsales), 0, 1, 'R');
		$this->setX($this->leftmargin);
		$this->Cell(45, 5, 'Total Disc:', 0, 0, 'R'); 
		$this->Cell(40, 5, number_format($this->data->totaldiscount), 0, 1, 'R');
		$this->setX($this->leftmargin);
		$this->Cell(45, 5, 'Total :', 0, 0, 'R');
		$this->Cell(40, 5, number_format($this->data->amount), 0, 1, 'R');
	}
	
	public function header()
	{
		$this->master();
	}
	
	public function footer()
	{
		/*$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('Courier', 'B');
		$this->SetFontSize(10);
		$this->setXY(1, 115);
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
		*/
	}
	
	public function master()
	{
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetCellPadding(0.8);
			
		$this->setXY($this->leftmargin, 5);
		$this->Cell(80, 5, 'KOSAYU - Pusat Oleh-oleh BALI', 0, 1, 'C');
		$this->setX($this->leftmargin);
		$this->Cell(80, 5, 'Jl Sunset Road no. 88x Kuta, Badung - Bali', 'B', 1, 'C');
		$this->Ln(2);
		$this->setX($this->leftmargin);
		$this->Cell(15, 5, 'Tanggal:'); $this->Cell(40,5, $this->data->idatetime);
		$this->Cell(15, 5, 'Sticker:'); $this->Cell(10,5, $this->data->idsticker, 0, 1);
		$this->setX($this->leftmargin);
		$this->Cell(15, 5, 'Mitra:'); $this->Cell(40,5, lookup::PartnerNameFromID($this->data->idpartner));
		$this->Cell(15, 5, 'Posisi:'); $this->Cell(10, 5, lookup::DetailPartnerNameFromID($this->data->idcomp), 0, 1);
		$this->Ln();
	} 	
}

function execute($model, $detailmodel, $detailmodel2) {

	// create new PDF document
	
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(140, 90), true, 'UTF-8', false);
	$pdf->pagesize = array(90, 140);
	$pdf->pageorientation = 'P';
	$pdf->leftmargin = 5;
	$pdf->setPageOrientation($pdf->pageorientation, TRUE);	
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor(lookup::UserNameFromUserID(Yii::app()->user->id));
	$pdf->SetTitle('Print Pembayaran Komisi');
	$pdf->SetSubject('Bayar Komisi Agen');
	$pdf->SetKeywords('Bayar Komisi Agen');
	
	//$pdf->setPrintHeader(false);
	//$pdf->setPrintFooter(false);
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(1, 40, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(0);
	$pdf->SetFooterMargin(0);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, 5);
	
	//set image scale factor
	$pdf->setImageScale(2.8);
	
	//set some language-dependent strings
	//$pdf->setLanguageArray($l);
	
	// ---------------------------------------------------------
	
	// set font
	//$pdf->SetFont('helvetica', '', 11);
	
	// add a page
	$pdf->LoadData($model, $detailmodel, $detailmodel2);
	
	$pdf->AddPage($pdf->pageorientation, $pdf->pagesize);
	//$pdf->AddPage();
	
	$pdf->ColoredTable();
	//$pdf->master();
	// print colored table
	
	// ---------------------------------------------------------
	
	//Close and output PDF document
	$pdf->Output('Print Komisi Agen-'.$model->regnum.'.pdf', 'D');
}
//============================================================+
// END OF FILE                                                
//============================================================+
?>

