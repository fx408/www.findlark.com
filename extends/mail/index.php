<?php

$f = function_exists("imap_open");

var_dump($f);
exit();
$mailbox = imap_open('{imap.gmail.com:993/imap/ssl}INBOX', 'fx4084@gmail.com', 'fx19890410');

for($i=1;$i <=5; $i++){
	$header = imap_header($mailbox, $test[$i-1]); 
	$from = $header->from; 
	print(" <PRE>"); 
	print("Header Date : " . date("Y-m-d h:i A",strtotime($header->Date)) .$i. " <BR>"); 
	print("Header To : " . iconv_mime_decode($header->toaddress,2,"UTF-8") . " <BR>"); 
	print("Header From : " . iconv_mime_decode($header->fromaddress,2,"UTF-8") . " <BR>"); 
	print("Header Subject : " . iconv_mime_decode($header->Subject,2,"UTF-8") . " <BR> </PRE>"); 
	print(" </PRE> <HR>"); 
}