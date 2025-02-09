<?php
/*
* @package Match
* @author Carsten Wallenhauer <admin@datapool.info>
* @copyright 2024 to today Carsten Wallenhauer
* @license https://opensource.org/license/mit MIT
*/

declare(strict_types=1);

namespace SourcePot\Match;

final class Helper{

    function __construct()
    {
        
    }

    public function value2html($val,string $caption='Caption'):string
    {
        if (!is_array($val)){
            $val=['value'=>$val];
        }
        foreach($val as $arrKey=>$arrVal){
            if (is_array($arrVal)){
                $val[$arrKey]=$this->arr2htmlTable($arrVal,$arrKey);
            }
        }
        return $this->arr2htmlTable($val,$caption);
    }

    private function arr2htmlTable(array $arr,string $caption='Caption'):string
    {
        $html='<table>';
        $html.='<caption>'.$caption.'</caption>';
        foreach($arr as $key=>$value){
            $html.='<tr><td>'.$key.'</td><td>'.$value.'</td></tr>';
        }
        $html.='</table>';        
        return $html;
    }

    public function string2number($string):float|bool
    {
        // recover value

        
        preg_match('/[+\-]{0,1}[0-9,.]+[eE+\-]{0,2}[0-9]+/',$string,$match);
        if (!isset($match[0])){return FALSE;}
        $numberStr=$match[0];
        $chrArr=count_chars($numberStr,1);
        if (($chrArr[44]??0)>1){$numberStr=str_replace(',','',$numberStr);}
        if (($chrArr[46]??0)>1){$numberStr=str_replace('.','',$numberStr);}
        $commaPos=strrpos($numberStr,',');
        $dotPos=strrpos($numberStr,'.');
        // 10,000 -> 10000 If the value has an ambiguous structure, English format is assumed 
        if ($commaPos!==FALSE && $dotPos===FALSE){
            $commaChunks=explode(',',$numberStr);
            if (strlen($commaChunks[0])<3 && strlen($commaChunks[1])===3){
                $numberStr=str_replace(',','',$numberStr);
                $commaPos=FALSE;
            }
        }
        if ($commaPos!==FALSE && $dotPos!==FALSE){
            if ($commaPos>$dotPos){
                // 1.000,00 -> 1000.00
                $numberStr=str_replace('.','',$numberStr);
                $numberStr=str_replace(',','.',$numberStr);
            } else {
                // 1,000.00 -> 1000.00
                $numberStr=str_replace(',','',$numberStr);
            }
        } else if ($commaPos!==FALSE){
            // 1,000 -> 1.000
            $numberStr=str_replace(',','.',$numberStr);
        }
        return floatval($numberStr);
    }

}
?>