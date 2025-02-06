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

$valueA=$_POST['valueA']??'2024-04-23';
$valueB=$_POST['valueB']??'2024-04-23 14:52:12';
$matchtype=$_POST['matchtype']??'dateTime';

require_once('../php/MatchValues.php');
$matchObj = new MatchValues();

// compile html
$html='<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml" lang="en"><head><meta charset="utf-8"><title>Match</title><link type="text/css" rel="stylesheet" href="index.css"/></head>';
$html.='<body><form name="892d183ba51083fc2a0b3d4d6453e20b" id="892d183ba51083fc2a0b3d4d6453e20b" method="post" enctype="multipart/form-data">';
$html.='<h1>Evaluation Page for the Match-Package</h1>';
$html.='<div class="control"><h2>Value A, Value B and Match-type setting</h2>';
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

require_once('../php/Helper.php');
$helperObj = new Helper();

if ($matchtype=='unycom'){
    require_once('../php/UNYCOM.php');
    $unycomObj = new UNYCOM();
    $unycomObj->set($valueA);  
    $html.=$helperObj->value2html($unycomObj->get(),'UNYCOM value A');
    $unycomObj->set($valueB);  
    $html.=$helperObj->value2html($unycomObj->get(),'UNYCOM value B');
}

$matchObj->set($valueA,$matchtype);
$match=$matchObj->match($valueB);
$html.=$helperObj->value2html($matchObj->get(),'Match');

$html.='<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>';
$html.='<script src="index.js"></script>';
$html.='</body></html>';
echo $html;

?>