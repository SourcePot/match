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

}
?>