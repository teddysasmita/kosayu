<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of idmaker
 *
 * @author teddy
 */

class Go_ODBC extends CComponent 
{
   //put your code here
   
   	public static function openSQL($sql)
   	{
   		$result = array();
   		
   		$dsn = "MSSQLServer";
		$user = "sa";
		$password = "";
   		
		$connect = odbc_connect($dsn, $user, $password);
   		
		$result = odbc_exec($connect, $sql);
				
   		while($boom = odbc_fetch_array($result)) {
   			$myresult[] = $boom;
   		}
   		
   		odbc_close($connect);

   		return $myresult;
   }
}

?>
