<?php

class Print2Text extends CComponent
{
	public $pageWidth = 40;
	public $filler = ' ';
	private $curX = 0;
	private $curY = 0;
	private $data = array();
	
	public function printText($x, $width, $text, $align = 'L', $ln = 0)
	{
		if (!isset($this->data[$this->curY]))
			$this->data[$this->curY] = '';
		for($i=0; $i < $x-$this->curX; $i++)
			$this->data[$this->curY] .= $this->filler;
		
		if ($align == 'L')
			$format = '%'.'-'."$width.$width".'s';
		$data[$this->curY] .= sprintf($format, $text);
		if ($ln == 1) {
			$this->data[$this->curY] .= '\n';
			$this->curY += 1;
		}
	} 
	
	public function sendOutput()
	{
		for($i=0; $i < $this->curY; $i++) {
			echo $this->data[$i].'\n';	
		}
	}
}

?>