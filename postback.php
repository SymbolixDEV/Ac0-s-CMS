<?php
DEFINE('BASEPATH', '');
include ("application/config/config.php");
include ("application/config/database.php");

if(function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get")) 
	@date_default_timezone_set(@date_default_timezone_get()); 
set_time_limit(15); 


$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}

$header = '';
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen('ssl://www.paypal.com',443,$err_num,$err_str,30);  

if (!$fp) 
{
	$handle = fopen('sock.txt', 'w+');
	fwrite($handle, 'Invalid Sock'); 
	fclose($handle);
} 
else 
{
	$handle = fopen('sock.txt', 'w+');
	fwrite($handle, 'Valid Sock'); 
	fclose($handle);
	
	fputs ($fp, $header . $req);
	if (!feof($fp)) 
	{
		$Con = mysql_connect($db_config['hostname'],$db_config['username'],$db_config['password']);  
		mysql_select_db($db_config['cms_db'],$Con);  
		$res = fgets ($fp, 1024);
		if (strcmp ($res, "OK") != 0) 
		{
			$Itemcount = ceil($_POST['quantity']);  
			$acctid = $_POST['item_number'];  
			$Money = (float)$_POST['mc_gross'];    /*Prevent txnid recycling.*/  
			$res = mysql_query("SELECT COUNT(*) FROM paypal_data WHERE txnid = '{$_POST['txn_id']}'",$Con);  
			if(mysql_result($res,0) != 0)  
			{   
				mysql_query("INSERT INTO paypal_data (username,txnid,amount,who,whendon,comment) VALUES ('{$_POST['item_number']}','{$_POST['txn_id']}','{$_POST['mc_gross']}','{$_POST['payer_email']}','".date("U")."','Fail: This transaction id is a duplicate.')",$Con);   
				die;  
			}
			if($_POST['payment_status'] != "Completed")  
			{   
				mysql_query("INSERT INTO paypal_data (username,txnid,amount,who,whendon,comment) VALUES ('{$_POST['item_number']}','{$_POST['txn_id']}','{$_POST['mc_gross']}','{$_POST['payer_email']}','".date("U")."','<span class=\"colorbad\">Fail:</span> This transaction is not completed but ".$_POST['payment_status'].".')",$Con);   
				die;  
			} 
			$Info = "<span class=\"colorgood\">Successful transaction!</span>"; 
			
			$query1_b=mysql_query("SELECT dp FROM account_addition WHERE id='".$acctid."' LIMIT 1",$Con);   
			if (mysql_num_rows($query1_b)=='0')   
			{     
				$Info.='<br><span class=\"colorbad\">No additional data! (account_addition table) DB: "'.$db_config['cms_db'].'". SQL: '."SELECT dp FROM account_addition WHERE username=\'".$query_a['username']."\' LIMIT 1</span>";   
			}  
			else   
			{   
				$query_b=mysql_fetch_assoc($query1_b);      
				if ($Money<>$Itemcount)    
				{     
					$Info.='<br><span class=\"colorbad\">Hack attempt:</span> Money donated ('.$Money.') is not equal to number of items ('.$Itemcount.')';    
				}    
				else    
				{    
					mysql_query("UPDATE account_addition SET dp='".($query_b['dp']+$Itemcount)."' WHERE id= '". $acctid."' LIMIT 1",$Con);     
					$Info.='<br>Query executed: '."UPDATE account_addition SET dp=\'".($query_b['dp']+$Itemcount)."\'  WHERE username= \'". $acctid."\' LIMIT 1<br>Everyhing went successfully.";    
				}   
			}  
			mysql_query("INSERT INTO paypal_data (username,txnid,amount,who,whendon,comment) VALUES ('{$_POST['item_number']}','{$_POST['txn_id']}','{$_POST['mc_gross']}','{$Itemcount}[|]{$_POST['payer_email']}','".date("U")."','{$Info}')",$Con);  
		}
		else
		{
			$Info = ""; 
			foreach($_POST as $key => $value)  
			{   
				$Info .= "{$key} = {$value} <br>\n";  
			}  
			mysql_query("INSERT INTO paypal_data (whendon,comment) VALUES ('".date("U")."','An invalid request was made. Postdata info:<br>\n{$Info}')",$Con);  
			mysql_close($Con); 
		}
		mysql_close($Con); 
	}
	fclose ($fp);
}

?>