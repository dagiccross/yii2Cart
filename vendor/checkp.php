<?php
$validFunctions = array("getPostCode", "checkPostalCSV");

$functName = $_REQUEST['f'];
$postal = $_REQUEST['p'];

if(in_array($functName,$validFunctions))
{
	$functName();
}

function checkPostalCSV()
{
	$tempPostal = $_REQUEST['p'];
	$filename = "../uploads/postcode.csv";
	$h = fopen("$filename","r");

	$isExists = "false";

	while (!feof ($h))
	{
		list($postcodeList)= fgetcsv($h);

		if (strtoupper(removeSpace($tempPostal)) == strtoupper(removeSpace($postcodeList)))
		{
			$isExists = "true";
			break;
		}
	}
	echo $isExists;
}

function removeSpace($str)
{
	return preg_replace('/( *)/', '', $str);
}
?>
