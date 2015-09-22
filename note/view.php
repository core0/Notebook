<?php
/*$path_to_dir_profiles = "profiles/";
$path_to_template = "default.xml";

if (isset($_COOKIE["log"]))
{
	$login = $_COOKIE["log"];
}
else 
{
	header("Location: /401.html");	
}*/
header("Content-Type: text/txt; charset=utf-8");
$doc = new DOMDocument();
$doc->load("profiles/note.xml");

require 'password.php';

$mynotes = $doc->getElementsByTagName("note");

foreach($mynotes as $nt){
	if($nt->attributes->getNamedItem("name")->nodeValue === $_GET["name"]){
		echo $nt->nodeValue;               
		die();             
	}
}
	
echo "not found";
?>