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
   
	private $dsn = "MSSQLServer";
	private $user = "sa";
	private $password = "";
	
   	public static function openSQL($sql)
   	{
   	# connect to a DSN "MSSQLTest" with a user "cheech" and password "chong"
   		$connect = odbc_connect($this->dsn, $this->user, $this->password);
   		
   			# perform the query
   		$result = odbc_exec($connect, $sql);
   		
   	# fetch the data from the database
   		while($boom = odbc_fetch_array($result)) {
   			$myresult[] = $boom;
   		}
   		
   	# close the connection
   		odbc_close($connect);

   		return $myresult;
   }
}

?>
