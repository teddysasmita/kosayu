<?php


require_once('config/lang/eng.php');
//require_once('tcpdf.php');

// extend TCPF with custom functions


class Barcodeprintpdf extends TCPDF {

	private $barcodetype;
	private $detaildata;
	private $style;
	private $labelwidth;
	private $labelheight;

	// Load table data from file
	public function LoadData($barcodetype, $labelwidth, $labelheight, array $detaildata) {
		// Read file lines
		$this->barcodetype = $barcodetype;
		$this->detaildata = $detaildata;
		$this->labelwidth = $labelwidth;
		$this->labelheight = $labelheight;
	}

	// Colored table
	public function drawBarcodes() {
		// Colors, line width and bold font
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFontSize(7);
		// Data
		$fill = 0;
		$counter=0;
		$iditem='';
		$margin=$this->getMargins();
		$this->style['vpadding'] = 3;
		$this->style['hpadding'] = 2;
		for($i=0; $i<count($this->detaildata); $i++) {
			unset($brand);
			unset($price);
			$price = 'Rp '.number_format(lookup::ItemPriceFromItemCode($this->detaildata[$i]['num']));
			$brand = lookup::ItemNameFromItemCode($this->detaildata[$i]['num']);
			//$this->style['label'] = $this->detaildata[$i]['num'].' - '.$price;
			
			if (($this->GetX() + $this->labelwidth) >= ($this->getPageWidth()- $margin['right'])) 
				$this->Ln((int)$this->labelheight);
			$this->checkPageBreak($this->labelheight);
			$tempx = $this->GetX();
			$tempy = $this->GetY();
			$this->Cell($this->labelwidth, 3, $brand, 0, 0, 'C');
			$this->setXY($tempx, $tempy + $this->labelheight-4);
			$this->Cell($this->labelwidth, 3, $price, 0, 0, 'C');
			$this->setXY($tempx, $tempy);
			$this->write1DBarcode($this->detaildata[$i]['num'], $this->barcodetype,
					'', '', $this->labelwidth, $this->labelheight, 0.4, $this->style, 'T');
		};	
	}

	public function init()
	{
		$this->SetCreator(PDF_CREATOR);
		$this->SetAuthor(lookup::UserNameFromUserID(Yii::app()->user->id));
		$this->SetTitle('Cetak Barcode');
		$this->SetSubject('Cetak Barcode');
		$this->SetKeywords('Cetak Barcode');
		
		// set default header data
		$this->setHeaderData(false, 0, 'KOSAYU',
				'Cetak Barcode', 'Testing');
		
		// set header and footer fonts
		$this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$this->setPrintFooter(false);
		
		// set default monospaced font
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		//set margins
		$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$this->SetHeaderMargin(PDF_MARGIN_HEADER);
		$this->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		//set auto page breaks
		$this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		//set image scale factor
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		//set some language-dependent strings
		//$this->setLanguageArray($l);
		
		// ---------------------------------------------------------
		
		// set font
		$this->SetFont('helvetica', '', 12);
		
		// add a page
		//$this->AddPage();
		$this->style = array(
				'position' => '',
				'align' => 'C',
				'stretch' => false,
				'fitwidth' => true,
				'cellfitalign' => '',
				'border' => true,
				'hpadding' => 'auto',
				'vpadding' => 'auto',
				'fgcolor' => array(0,0,0),
				'bgcolor' => false, //array(255,255,255),
				'text' => true,
				'font' => 'helvetica',
				'fontsize' => 8,
				'stretchtext' => 4	
		);
		
	}
	
	public function display()
	{
		$this->addPage();
		$this->drawBarcodes();
		//$this->Output('KartuStok'.$this->itemname.'-'.$this->warehousecode.'-'.date('Ymd').'.pdf', 'D');
	}
}
?>