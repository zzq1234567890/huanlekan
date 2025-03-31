<?php
ini_set("max_execution_time", "3000000");
//header('Content-Type: text/html; charset=us-ascii');
header( 'Content-Type: text/plain;charset=UTF-8');
//header( 'Content-Type: text; charset=UTF-8');
$url='http://192.168.10.1/news.txt';
$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, $url);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch1, CURLOPT_POST, 1);
//curl_setopt($ch1, CURLOPT_POSTFIELDS, $idk);
//curl_setopt($ch1, CURLOPT_HTTPHEADER, $header1);
curl_setopt($ch1,CURLOPT_ENCODING,'Vary: Accept-Encoding');
$result = curl_exec($ch1);
curl_close($ch1);

header("Content-Disposition: attachment; filename=faintv.txt");
//print $result;
for ( $i=1 ; $i<=count(json_decode($result)->data) ; $i++ ) {

$id = json_decode($result)->data[$i-1]->_id;
$name= json_decode($result)->data[$i-1]->name;
  print "#EXTINF:-1 tvg-id=\"".$id."\" tvg-name=\"".$idE."\" tvg-logo=\"".$id."\" group-title=\"4gtv\",".$id."\r\n";
//print "#EXTINF:-1 tvg-id=".$fs4GTV_ID." tvg-name=".$fsNAME." tvg-logo=".$fsLOGO_MOBILE." group-title=4gtv,".$fsNAME."\r\n";
print "http://127.0.0.1:8081/huanlekan.php?id=".$name."\n";
}
?>
