<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Print Out Belakang Kartu Guide</title>
<style>
	@page { size: <?php echo $model->paperwidth; ?>mm <?php echo $model->paperheight; ?>mm;
		margin-bottom: <?php echo $model->paperbotm; ?>mm;
		margin-top: <?php echo $model->paperbotm; ?>mm; 
		margin-right: <?php echo $model->papersidem; ?>mm;
		margin-left: <?php echo $model->papersidem; ?>mm;
		page-break-before: 5mm;
	}
	
	.guidetable { 
		/*margin-left: <?php echo $model->paperwidth - ( 3 * $model->papersidem) - $model->labelwidth - (2 * $model->labelsidem); ?>mm;*/ 	
	}
	
	.guidetablerow { 
		height: <?php echo $model->labelheight; ?>mm;
		margin: <?php echo $model->labelbotm; ?>mm; 
	}
	.guidetablecol { 
		width: <?php echo $model->labelwidth; ?>mm;
		margin: <?php echo $model->labelsidem; ?>mm;
		 
	}
	.guidetablecell {  
		border: solid thin;
		-webkit-print-color-adjust: exact;
	 }
	 .cardcontent {
	 	background-color: red;
	 	height: <?php echo $model->labelheight-4; ?>mm;
	 	width: <?php echo $model->labelwidth-4; ?>mm;
	 	margin-top: <?php echo $model->labelbotm; ?>mm;
	 	margin-bottom: <?php echo $model->labelbotm; ?>mm;
	 	margin-right: <?php echo $model->labelsidem; ?>mm;
	 	margin-left: <?php echo $model->labelsidem; ?>mm;
	 	padding: 2mm;
	 	border: solid thin;
	 	float: right;
	 	page-break-inside: avoid;
	 }
	 .emptycontent {
	 	height: <?php echo $model->labelheight-2; ?>mm;
	 	width: <?php echo $model->labelwidth-2; ?>mm;
	 }
	 
	 .infofield {
	 	margin: 10mm; width: 25mm;
	 	font-family: monospace;
	 	font-size: 10pt;
	 	background-color: #f3781b;
	 }
	 .infovalue {
	 	margin: 10mm; width: 75mm;
	 	font-family: serif;
	 	font-size: 11pt;
	 }
	 .cardtitle {
	 	text-align: center;
	 	height: 10mm;
	 }
	 .barcodeinfo {
	 	text-align: center;
	 	/*padding-top: 4mm;*/
	 }
	 .addressinfo {
	 	font-family: sans-serif;
	 	font-size: 8pt;
	 	text-align: center;
	 }
	 .bgimg {
	 	margin-left: 12.5mm;
	 	margin-top: 5mm;
	 }
	 .backimage {
	 	align-text: center;
	 }
	 
</style>
</head>
<body>
    <?php
    	function printGuideCard($idguide) {
    		$guide = lookup::getGuide($idguide['idguide']);
    		if (!$guide)
    			return 'Data Invalid';
    		else {
    			$image = imagecreatefromjpeg('protected/modules/idguideprint/components/background.jpg');
    			$imagepath=Yii::app()->getAssetManager()->publish('protected/modules/idguideprint/components/background.jpg'); 
    			$data = <<<EOS
    		<table>
    		<tr><td class="backimage"><img class="bgimg" src="$imagepath" height=150 width=200>
			</table>
EOS;
    			return $data;	
    		}
    	}
    
    	/*if ($paperinfo['rownum'] > 0) 
    		echo "<TABLE class=\"guidetable\">";
    	*/
    	/*if ($paperinfo['colnum'] > 0)
    		echo "<COLGROUP>";
    	*/
    	/*for ($j=0; $j<$paperinfo['colnum']; $j++) {
    		$y = str_pad($j, 2, '0', STR_PAD_LEFT);	
    		echo "<COL id=\"COL$y\" class=\"guidetablecol\">";
		}
		$cardcount = 0;
		for ($k=0; $k < $paperinfo['pagenum']; $k ++) {
			for ($i=0; $i < $paperinfo['rownum']; $i++) {
				$y = str_pad($i, 2, '0', STR_PAD_LEFT);
				echo "<TR class=\"guidetablerow\">";
				for ($j=0; $j < $paperinfo['colnum']; $j++) {
					$x = str_pad($j, 2, '0', STR_PAD_LEFT);
					if ($cardcount < count($details))
						echo "<TD class=\"guidetablecell\"><div class=\"cardcontent\">".
						printGuideCard($details[$cardcount])."</div>";
					else
						echo "<TD class=\"guidetablecell\"><div class=\"emptycontent\">EmptyCell$x$y";
					$cardcount += 1;
					//echo "<div class='guidetablecell' id='cell$x$y'>boom</div>";
				}
			}
		}*/
		$cardcount = 0;
		for ($k=0; $k < $paperinfo['pagenum']; $k ++) {
			for ($i=0; $i < $paperinfo['rownum']; $i++) {
				$posy = ($i * $model->labelheight) + $model->paperbotm;
				$y = str_pad($i, 2, '0', STR_PAD_LEFT);
				for ($j=0; $j < $paperinfo['colnum']; $j++) {
					$x = str_pad($j, 2, '0', STR_PAD_LEFT);
					$posx = ($j * $model->labelwidth) + $model->papersidem;
					if ($cardcount < count($details)) {
						echo "<div class=\"cardcontent\" id=\"card$x$y\">".
								printGuideCard($details[$cardcount])."</div>";
						$cardcount ++;
					}
				}
			}
		}			
					
    ?>
</body>
</html>