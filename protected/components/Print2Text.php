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
		for($i=0; $i < $x-$this->curX; $i++)
			$data[$this->curY] .= $this->filler;
		
		if ($align == 'L')
			$format = '%'.'-'."$width.$width".'s';
		$data[$this->curY] .= sprintf($format, $text);
		if ($ln == 1)
			$data[$ths->curY] .= '\n';
	} 
	
	public function sendOutput()
	{
		for($i=0; $i < $this->curY; $i++) {
			echo $data[$i].'\n';	
		}
	}
}

?>