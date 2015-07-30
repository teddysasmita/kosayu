<?php


//require_once('tcpdf.php');

// extend TCPF with custom functions
class MYPDF extends TCPDF {

	private $masterData;
	private $detailData;
	private $warehouses;
	
	// Load table data from file
	public function LoadData($id) {
		// Read file lines
		$this->masterData=Yii::app()->db->createCommand()
			->select()->from('inventorytakings')
			->where('id = :id', array(':id'=>$id))
			->queryRow();
		$this->detailData=Yii::app()->db->createCommand()
			->select('b.iditem, b.idwarehouse, c.name, sum(b.qty) as sumqty')
			->from('inputinventorytakings a')
			->join('detailinputinventorytakings b', 'b.id = a.id')
			->join('items c', 'c.id=b.iditem')
			->where('a.idinventorytaking = :id', array(':id'=>$id))
			->group('b.iditem, b.idwarehouse')
			->order('c.name')->queryAll();
	}

	// Colored table
	public function ColoredTable() {
		// Colors, line width and bold font
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', '');
		$this->SetFontSize(8);
		
		$this->warehouses=Yii::app()->db->createCommand()
			->select('id, code')->from('warehouses')->queryAll();
		$headernames = array('No', 'Nama Barang');
		$headerwidths = array(10, 100);
		foreach($this->warehouses as $wh) {
			$headernames[]=$wh['code'];
			$headerwidths[]=10;
			$warehouses[]=$wh['id'];
		}
		$headernames[]='Total';
		$headerwidths[]=15;
		for($i = 0; $i < count($headernames); ++$i) {
			$this->Cell($headerwidths[$i], 7, $headernames[$i], 1, 0, 'C', 1);
		}
		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = 0;
		$counter=1;
		$iditem='';
		for($j=0; $j<count($this->detailData); $j++) {
			$row=$this->detailData[$j];

			if (($iditem !== $row['iditem']) or ($j==count($this->detailData)-1)) {
				if ($iditem !== '') {
					for ($i=0; $i<count($warehouses); $i++) {
						if (isset($qtys[$warehouses[$i]]))
							$qty=$qtys[$warehouses[$i]];
						else
							$qty=0;
						$this->Cell($headerwidths[2+$i], 6, $qty, 'LR', 0, 'R', $fill);
					}
					$this->Cell($headerwidths[2+$i], 6, array_sum($qtys), 'LR', 0, 'R', $fill);
					$this->Ln();
					unset($qtys);
					$qtys=array();
				}
				$this->Cell($headerwidths[0], 6, $counter, 'LR', 0, 'C', $fill);
				$this->Cell($headerwidths[1], 6, lookup::ItemNameFromItemID($row['iditem']), 'LR', 0, 'L', $fill);
				
				//$fill=!$fill;
				$counter++;
				$iditem=$row['iditem'];
			}
			$qtys[$row['idwarehouse']]=$row['sumqty'];
			//$this->Cell($headerwidths[3], 6, $row['serialnum'], 'LR', 0, 'L', $fill);
		}
		for ($i=0; $i<count($warehouses); $i++) {
			if (isset($qtys[$warehouses[$i]]) )
				$qty=$qtys[$warehouses[$i]];
			else
				$qty=0;
			$this->Cell($headerwidths[2+$i], 6, $qty, 'LR', 0, 'R', $fill);
		}
		if (isset($qtys))
			$this->Cell($headerwidths[2+$i], 6, array_sum($qtys), 'LR', 0, 'R', $fill);
		else
			$this->Cell($headerwidths[2+$i], 6, 0, 'LR', 0, 'R', $fill);
		$this->Ln();	
		$this->Cell(array_sum($headerwidths), 0, '', 'T');
	}
	
	public function master()
	{
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B');
		$this->SetFontSize(20);
		
		$this->Cell(180, 20, 'Stok Opname', 0, 1, 'C');
	
		$this->setFontSize(12);
		//print_r($this->masterData);
		//die();
		$this->Cell(30, 7, 'Label', 'LT', 0, 'C', true);
		$this->Cell(70, 7, $this->masterData['operationlabel'], 'LRTB');
		$this->Cell(30, 7, 'Tanggal', 'LRT', 0, 'C', true);
		$this->Cell(50, 7, $this->masterData['idatetime'], 'LRT');
		$this->Ln();
		$this->SetFillColor(224, 235, 255);
		$this->Cell(30, 7, 'Catatan', 'LRBT', 0, 'C', true);
		$this->Cell(150, 14, $this->masterData['remark'],'LRTB');
		
		$this->Ln(20);
	} 
	
	
}



// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(lookup::UserNameFromUserID(Yii::app()->user->id));
$pdf->SetTitle('Laporan Stok Opname');
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

