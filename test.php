<?php
error_reporting(-1);

require 'adzerk.api.php';

$adzerk = new Adzerk('E10ADC3949BA59ABBE56E057F20F883E10'); // Sample API Key
$data = array(
	"Title"=> "Sample Advertiser",
	"IsActive"=> true
);

try {
	$rsp = $adzerk->advertiser(null, $data);
	print_r($rsp);
	$advertiserID = $rsp['Id'];
} catch (Exception $e){
	echo 'Connection Error #'.$e->getCode().': '.$e->getMessage();
}