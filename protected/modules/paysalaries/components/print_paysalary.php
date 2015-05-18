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
	public $leftmargin;
	
	// Load table data from file
	public function LoadData($data, array $detaildata) {
		// Read file lines
		$this->data = $data;
		$this->detaildata = $detaildata;
		
		$this->headernames1 = array('Komponen', 'Jumlah' );
		$this->headerwidths1 = array(50, 20);		
	}

	// Colored table
	public function ColoredTable() {
		// Colors, line width and bold font
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->setfontsize(10);

		$this->SetXY($this->leftmargin, 50);
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
			$ih = $this->getStringHeight($this->headerwidths1[1],$row['componentname'], false, true, 2);
			$this->Cell($this->headerwidths1[0], $ih, lookup::getComponentName($row['componentname'], 0, 0, 'L'));
			$this->Cell($this->headerwidths1[1], $ih, number_format($row['amount']), 0, 0, 'R');
			$this->ln($ih);
			$this->checkPageBreak($ih);
		}
			
		$this->SetFontSize(11);
		$this->setX($this->leftmargin);
		$this->Cell(45, 5, 'Total:', 0, 0, 'R'); 
		$this->Cell(40, 5, number_format($this->data->total), 0, 1, 'R');
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

		$employeeinfo = Yii::app()->db->createCommand()
		->select()->from('employees')
		->where('id = :p_id', array(':p_id'=>$this->data->idemployee))
		->queryRow();
		
		$this->setXY($this->leftmargin, 5);
		$this->Cell(70, 5, 'SLIP GAJI', 0, 1, 'C');
		$this->Ln(2);
		$this->setX($this->leftmargin);
		$this->Cell(30, 5, 'Tanggal:'); $this->Cell(40,5, $this->data->idatetime, 0, 1);
		$this->setX($this->leftmargin);
		$this->Cell(30, 5, 'Nama:'); $this->Cell(50,5, lookup::EmployeeNameFromID($this->data->idemployee), 0, 1);
		$this->setX($this->leftmargin);
		$this->Cell(30, 5, 'Gaji Pokok:'); $this->Cell(50,5, number_format($employeeinfo['wageamount']), 0, 1);
		$this->setX($this->leftmargin);
		$this->Cell(30, 5, 'Jumlah Hadir (hr):'); $this->Cell(10,5, $this->data->presence, 0, 1);
		$this->setX($this->leftmargin);
		$this->Cell(30, 5, 'Jumlah Lembur (mnt):'); $this->Cell(10, 5, $this->data->overtime, 0, 1);
	} 	
}

function execute($model, $detailmodel) {

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
	$pdf->SetMargins(1, 60, PDF_MARGIN_RIGHT);
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
	$pdf->LoadData($model, $detailmodel);
	
	$pdf->AddPage($pdf->pageorientation, $pdf->pagesize);
	//$pdf->AddPage();
	
	$pdf->ColoredTable();
	//$pdf->master();
	// print colored table
	
	// ---------------------------------------------------------
	
	//Close and output PDF document
	$pdf->Output('Print Slip Gaji -'.$model->regnum.'.pdf', 'D');
}
//============================================================+
// END OF FILE                                                
//============================================================+
?>

