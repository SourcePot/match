<?php
/*
* This file creates an HTML page as user interface to play with the asset class
* @package email
* @author Carsten Wallenhauer <admin@datapool.info>
* @copyright 2024 to today Carsten Wallenhauer
* @license https://opensource.org/license/mit MIT
*/
	
declare(strict_types=1);
	
namespace SourcePot\Match;
	
mb_internal_encoding("UTF-8");

require_once('../php/MatchValues.php');
$matchObj = new MatchValues();

$valueA=$_POST['valueA']??'';
$valueB=$_POST['valueB']??'';
$matchtype=$_POST['matchtype']??'';
// compile html
$html='<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml" lang="en"><head><meta charset="utf-8"><title>Asset</title><link type="text/css" rel="stylesheet" href="index.css"/></head>';
$html.='<body><form name="892d183ba51083fc2a0b3d4d6453e20b" id="892d183ba51083fc2a0b3d4d6453e20b" method="post" enctype="multipart/form-data">';
$html.='<h1>Evaluation Page for the Match-Package</h1>';
$html.='<div class="control"><h2>Asset properties for instantiation</h2>';
$html.='<input type="text" value="'.$valueA.'" name="valueA" id="valueA" style="margin:0.25em;"/>';
$html.='<input type="text" value="'.$valueB.'" name="valueB" id="valueB" style="margin:0.25em;"/>';
$html.='<select name="matchtype" id="matchtype">';
foreach($matchObj->getMatchTypes() as $id=>$name){
    $selected=($id===$matchtype)?' selected':'';
    $html.='<option value="'.$id.'"'.$selected.'>'.$name.'</option>';
}
$html.='</select>';

$html.='<input type="submit" name="test" id="set" style="margin:0.25em;" value="Set"/></div>';
$html.='</div>';
$html.='</form>';

require_once('../php/UNYCOM.php');
$unycomObj=new UNYCOM();
$unycomObj->setCase('IIS1 - 2021P62746WEGB04');


// print asset
/*
$html.='<table>';
$html.='<caption>Asset instance ['.$unit.']</caption>';
foreach($asset->getArray() as $key=>$value){
    if (is_object($value)){
        $value=$value->format('Y-m-d');
    }
    $html.='<tr><td>'.$key.'</td><td>'.$value.'</td></tr>';
}
$html.='</table>';
*/

$html.='<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>';
$html.='<script src="index.js"></script>';
$html.='</body></html>';
echo $html;

?>