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
	private $maxrows = 10;
	private $total = 0;
	
	public $pageorientation;
	public $pagesize;
	
	// Load table data from file
	public function LoadData($data, array $detaildata) {
		// Read file lines
		$this->data = $data;
		$this->detaildata = $detaildata;
		$this->headernames = array('Kode', 'Nama Barang', 'Jmlh', 'Harga@', 'Total');
		$this->headerwidths = array(30, 80, 15, 30, 30);
	}

	// Colored table
	public function ColoredTable() {
		// Colors, line width and bold font
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('Helvetica', 'B');
		$this->SetFontSize(10);
		
		// Data
		$fill = 0;
		$counter=0;
		$iditem='';
		$this->SetXY(10, 42);
		if (count($this->detaildata) <= $this->maxrows)
			$maxrows = $this->maxrows;
		else
			$maxrows = count($this->detaildata);		
		for($i=0;$i<$maxrows;$i++) {
			$this->setX(10);
			if ($i<count($this->detaildata)) {
				$row=$this->detaildata[$i];
				
				$counter+=1;
				$this->Cell($this->headerwidths[0], 6, $row['batchcode'], 'LR', 0, 'C', $fill);
				$this->Cell($this->headerwidths[1], 6, lookup::ItemNameFromItemID($row['iditem']), 
						'LR', 0, 'L', $fill);
				$this->Cell($this->headerwidths[2], 6, number_format($row['qty']), 'LR', 0, 'R', $fill);
				$this->Cell($this->headerwidths[3], 6, number_format($row['price']), 
						'LR', 0, 'R', $fill);
				$total = $row['qty'] * $row['price'];
				$this->total += $total;
				$this->Cell($this->headerwidths[4], 6, number_format($total), 'LR', 1, 'R', $fill);
			} else {
				$this->Cell($this->headerwidths[0], 6, ' ', 'LR', 0, 'C', $fill);
				$this->Cell($this->headerwidths[1], 6, ' ', 'LR', 0, 'L', $fill);
				$this->Cell($this->headerwidths[2], 6, ' ', 'LR', 0, 'R', $fill);
				$this->Cell($this->headerwidths[3], 6, ' ', 'LR', 0, 'R', $fill);
				$this->Cell($this->headerwidths[4], 6, ' ', 'LR', 1, 'R', $fill);
				//$this->ln();
			}
			$this->setX(10);
			if (($i > 0) && ($i % ($this->maxrows-1) == 0)) {
				//$this->checkPageBreak(6, '');
				//$this->Cell(array_sum($this->headerwidths), 0, '', 'T', 1);
				
			}
		}
		$ih = $this->getStringHeight(185, $this->data->remark);
		$this->checkPageBreak($ih + 5);
		$this->setX(10);
		$this->Cell(120, 5, 'Total', 'LTB', 0, 'R');
		$this->Cell(65, 5, number_format($this->total), 'LTBR', 1, 'R');
		$this->setX(10);
		$this->MultiCell(185, $ih, $this->data->remark, 'LTRB');
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
		$this->Cell(185, 10, 'Nota Pemesanan Barang', 'LTR', 1, 'C');
		$this->SetFontSize(10);
		$this->SetFont('Helvetica', 'B');
		$this->setXY(10, 20);
		$this->Cell(20, 5, 'Tgl', 'LT', 0, 'C');
		$this->Cell(35, 5, substr($this->data->idatetime, 0, 10), 'LTR', 0, 'C');
		$this->Cell(20, 5, 'Nomor', 'LTR', 0, 'C');
		$this->Cell(40, 5, $this->data->regnum, 'LTRB', 0, 'C');
		$this->Cell(30, 5, 'Tgl Akhir', 'LTR', 0, 'C');
		$this->Cell(40, 5, substr($this->data->rdatetime, 0, 10), 'LTRB', 0, 'C');
		//space

		$this->setXY(10, 26);
		$this->Cell(20, 5, 'Pemasok', 'LTB', 0, 'C');
		$this->Cell(165, 5, lookup::SupplierNameFromSupplierID($this->data->idsupplier), 
			'LTRB', 1, 'C');
		$this->setXY(10, 31);
		
		
		
		/*$this->SetFont('Helvetica', 'B');
		$this->Cell(35, 5, 'Nama Penerima', 'LTR', 0,'C');
		$this->Cell(80, 5, $this->data->receivername, 'LTR');
		$this->SetFont('Helvetica', 'B');
		$this->Cell(30, 5, 'Telp Penerima', 'LTR', 0,'C');
		$this->Cell(50, 5, $this->data->receiverphone, 'LTR', 1);
		
		$this->SetFont('Helvetica', 'B');
		$this->Cell(35, 5, 'Alamat Penerima', 'LTR', 0,'C');
		$this->Cell(160, 5, $this->data->receiveraddress, 'LTR', 1);
		$this->SetFont('Helvetica', 'B');
		$this->Cell(35, 5, 'Info Kendaraan', 'LTRB', 0,'C');
		$this->Cell(160, 5, $this->data->vehicleinfo, 'LTRB', 1);
		*/
		
		$this->ln(5);
		$this->setX(10);
		$this->setFontSize(12);
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
	$pdf->SetTitle('Beli Putus');
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
	$pdf->SetMargins(1, 42, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(0);
	$pdf->SetFooterMargin(0);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, 25);
	
	//set image scale factor
	$pdf->setImageScale(2.8);
	
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
	$pdf->Output('Beli Konsinyasi'.idmaker::getDateTime().'.pdf', 'D');
}
//============================================================+
// END OF FILE                                                
//============================================================+
?>

