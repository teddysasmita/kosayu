<?php

$data=array();
$datafile;
$outputfile;

$mydb=new mysqli('localhost', 'root','test','gsi');

function processParam() 
{
   global $argc;
   global $argv;
   
   if($argc>2) {
      die('Only 1 argument is required.'.PHP_EOL);
   } else if($argc<2) {
      die('Item price data is required'.PHP_EOL);
   } else if($argv[1]=='help') {
      echo "itempricing.php <data file>".PHP_EOL;
   } else {
      if(!file_exists($argv[1])) { 
         die('Data file does not exist'.PHP_EOL);
      }
   }
}

function loadFiles() 
{
   global $datafile;
   global $outputfile;
   global $data;
   global $argc;
   global $argv;
   
   $datafile=fopen($argv[1],'r');
   
   while($row=fgets($datafile)) {
      $data[]=explode(',', trim($row));
   }
   
   fclose($datafile);
}

function getregnum() {
	global $mydb;

	$res = $mydb->query("select val from information where id = 'AC11'");
	if ($res !== FALSE ) {
		$boom = $res->fetch_row();
		return $boom[0];
	} else {
		return 'NA';
	};
	$res->free();
}

function saveregnum($regnum) {
	global $mydb;
	
	$mydb->query("update information set val = '$regnum' where id = 'AC11'");
}


function setSellingPrice($counter, $code, $sellprice, $name)
{
	global $mydb;
	
	$sql1 = <<<EOS
	insert into sellingprices (`id`, `regnum`,`idatetime`, `iditem`, `normalprice`, `minprice`, `approvalby`, `userlog`, `datetimelog`)
	values (?,?,?,?,?,?,?,?,?)
EOS;
	$stmtprice = $mydb->prepare($sql1);
	if (!$stmtprice) {
		echo $mydb->error."\n";
		die;
	}
	$id = date('YmdHis');
	$id = $id.str_pad($counter, 5, '0', STR_PAD_LEFT).'A';
	$idatetime = date('Y/m/d H:i:s');
	$regnum = getregnum();
	saveregnum($regnum+1);
	$userlog = '13926111914000000';
	$datetimelog = $idatetime;
	echo $code."-".$name."-";
	$res = $mydb->query("select id from items where code = '$code'");
	if ($res !== FALSE ) {
		$row = $res->fetch_assoc();
		if ($row !== FALSE) {
			$approvalby = 'Bp Welly T';
			$stmtprice->bind_param('sssssssss', $id, $regnum, $idatetime, $row['id'], $sellprice,
					$sellprice, $approvalby, $userlog, $datetimelog);
			$stmtprice->execute();
			echo $name."\n";
		};
		$res->close();
	} else {
		echo "fail to find code \n";
	}
	$stmtprice->close();
}

function processData()
{
	global $data;
	global $mydb;	
	global $outputfile;
	
	
	$temp = 1;
	date_default_timezone_set('Asia/Jakarta');
	foreach($data as $listrow) {
		$res = $mydb->query("select a.`id`, a.`normalprice` from `sellingprices` a join `items` b on b.`id` = a.`iditem` where b.`code` = '${listrow[0]}'");
		$boom = $res->fetch_assoc();
		$res->free();
		//print_r($boom);
		if ($boom !== FALSE) {
		  	if ($boom['normalprice'] !== $listrow[2] ) {
		  		echo $boom['normalprice']." -  ". $listrow[2]. " - ";
				setSellingPrice($temp, $listrow[0], $listrow[2], $listrow[1]);
	 	    } 
		} else {
			 setSellingPrice($temp, $listrow[0], $listrow[2], $listrow[1]);
		};
		$temp++;
	};  
}

processParam();
loadFiles();
processData();


?>
