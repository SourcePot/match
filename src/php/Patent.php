<?php
/*
* @package Match
* @author Carsten Wallenhauer <admin@datapool.info>
* @copyright 2024 to today Carsten Wallenhauer
* @license https://opensource.org/license/mit MIT
*/

declare(strict_types=1);

namespace SourcePot\Match;

final class Patent{

    private const PATENT_TEMPLATE=['ipRef'=>'','cc'=>'','number'=>'','needle'=>'','checkDigit'=>FALSE,'publicationType'=>FALSE,'isValid'=>FALSE];

    private $patent=self::PATENT_TEMPLATE;
    
    function __construct()
    {
        
    }

    /**
     * Getter methods
     */

    final public function __toString():string
    {
        return $this->patent['cc'].$this->patent['number'].$this->patent['publicationType'];
    }

    final public function get():array
    {
        return $this->patent;
    }
    
    final public function isValid():bool
    {
        return $this->patent['isValid'];
    }

    /**
     * Setter methods
     */

    final function set(string $value)
    {

        $this->patent=$this->value2patent($value);
    }

    /**
     * Feature methods
     */

    private function value2patent(string $value):array
    {
        $patent=self::PATENT_TEMPLATE;
        $patent['raw']=$value;
        // get a publication suffix
        preg_match('/[0-9 ]([A-Z][0-9]{0,1})/',$value,$match);
        $patent['publicationType']=$match[1]??'';
        if (isset($match[1])){
            $value=str_replace($match[1],'',$value);
        }
        $value=trim($value);
        // get/remove possible check digit
        $stringComps=preg_split('/[^A-Z0-9]+/',$value);
        $checkDigit=array_pop($stringComps);
        if (is_numeric($checkDigit) && strlen($checkDigit)===1){
            // check digit detected
            $patent['checkDigit']=intval($checkDigit);
        } else {
            $stringComps[]=$checkDigit;
        }
        // get needle
        $string=implode('',$stringComps);
        $patent['needle']=substr($string,-3);
        $patent['ipRef']=str_replace('PCT','WO',$string);
        if (empty($patent['ipRef'])){
            $patent['isValid']=FALSE;
            return $patent;
        }
        // get country code
        preg_match_all('/[^0-9]+/',$patent['ipRef'],$matches);
        $patent['cc']=$matches[0][0]??'';
        // get number
        preg_match_all('/[0-9]+/',$patent['ipRef'],$matches);
        $patent['number']=implode('',$matches[0]??[]);
        $patent['isValid']=TRUE;
        return $patent;
    }

    final public function match($value):float|int
    {
        $patentA=$this->get();
        $patentB=$this->value2patent($value);
        if (!$patentA['isValid'] || !$patentB['isValid']){return 0;}
        // country code match
        if ($patentA['cc'] == $patentB['cc']){
            $ccMatch=1;
        } else if ((empty($patentA['cc']) && !empty($patentB['cc'])) || (!empty($patentA['cc']) && empty($patentB['cc']))){
            $ccMatch=0.9;
        } else {
            $ccMatch=0;
        }
        // number match
        $strLenA=strlen($patentA['number']);
        $strLenB=strlen($patentB['number']);
        if ($strLenA-$strLenB===2){
            if (strpos($patentA['number'],$patentB['number'])===FALSE){
                $numberMatch=0;
                $ccMatch=0;
            } else {
                $numberMatch=1;
            }
        } else if ($strLenB-$strLenA===2){
            if (strpos($patentB['number'],$patentA['number'])===FALSE){
                $numberMatch=0;
                $ccMatch=0;
            } else {
                $numberMatch=1;
            }
        } else if ($strLenA===$strLenB){
            if ($patentA['number']===$patentB['number']){
                $numberMatch=1;
            } else {
                $numberMatch=0;
                $ccMatch=0;
            }
        } else {
            // number length mismatch other than 2
            $numberMatch=0;
            $ccMatch=0;
        }
        return $ccMatch*$numberMatch;
    }

}
?>