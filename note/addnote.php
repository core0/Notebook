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


header("Content-Type: text/html; charset=utf-8");
$doc = new DOMDocument();
$doc->load("profiles/note.xml");

require 'password.php';

$note = $doc->createElement("note");
$note->setAttribute("name",$_GET["name"]);

$mynotes = $doc->getElementsByTagName("notes");
$mynotes->item(0)->appendChild($note);

$doc->save("profiles/note.xml");
?>