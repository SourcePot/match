<?php
/*
* @package Match
* @author Carsten Wallenhauer <admin@datapool.info>
* @copyright 2024 to today Carsten Wallenhauer
* @license https://opensource.org/license/mit MIT
*/

declare(strict_types=1);

namespace SourcePot\Match;

require_once('../../vendor/autoload.php');

final class MatchValues{

    private $matchArr=NULL;

    private const MATCH_TYPES=['strpos'=>'Contains','stripos'=>'Contains (ci)','unycom'=>'UNYCOM case','dateTime'=>'DateTime'];
    
    function __construct()
    {
        $this->matchArr=['value'=>'','matchType'=>'','toMatchValue'=>'','match'=>0];
    }

    final public function __toString():string
    {
        if (is_array($this->matchArr['value'])){
            $string=json_encode($this->matchArr['value']);
        } else {
            $string=strval($this->matchArr['value']);
        }
        $string.=' '.$this->matchArr['matchType'].' ';
        if (isset($this->matchArr['toMatchValue'])){
            if (is_array($this->matchArr['toMatchValue'])){
                $string.=json_encode($this->matchArr['toMatchValue']);
            } else {
                $string.=strval($this->matchArr['toMatchValue']);
            }        
        }
        if (isset($this->matchArr['match'])){
            $string.=' → '.strval($this->matchArr['matchType']);
        }
        return trim($string);
    }

    /**
     * Getter methods
     */

     final public function get():array
     {
         return $this->matchArr;
     }

     final public function getHtml():array
     {
         return $this->matchArr;
     }
  
    /**
     * Setter methods
     */

    final public function set($value,string $matchType)
    {
        if (isset(self::MATCH_TYPES[$matchType])){
            $this->matchArr=['value'=>$value,'matchType'=>$matchType];
        } else {
            throw new \Exception("\"$matchType\" is not a valid match type"); 
        }
    }

    /**
     * Feature methods
     */

     final public function getMatchTypes():array
     {
         return self::MATCH_TYPES;
     }
 
     final public function match($toMatchValue):float
        {
        $match=0;
        $this->matchArr['toMatchValue']=$toMatchValue;
        if ($this->matchArr['matchType']==='strpos'){
            if (mb_strlen($this->matchArr['value'])>mb_strlen($this->matchArr['toMatchValue'])){
                $this->matchArr['match']=(mb_strpos($this->matchArr['value'],$this->matchArr['toMatchValue'])===FALSE)?0:1;
            } else {
                $this->matchArr['match']=(mb_strpos($this->matchArr['toMatchValue'],$this->matchArr['value'])===FALSE)?0:1;
            }
        } else if ($this->matchArr['matchType']==='stripos'){
            if (mb_strlen($this->matchArr['value'])>mb_strlen($this->matchArr['toMatchValue'])){
                $this->matchArr['match']=(mb_stripos($this->matchArr['value'],$this->matchArr['toMatchValue'])===FALSE)?0:1;
            } else {
                $this->matchArr['match']=(mb_stripos($this->matchArr['toMatchValue'],$this->matchArr['value'])===FALSE)?0:1;
            }
        } else if ($this->matchArr['matchType']==='unycom'){
            $unycomObj = new \SourcePot\Match\UNYCOM();
            $unycomObj->set($this->matchArr['value']);
            $this->matchArr['match']=$unycomObj->match($toMatchValue);
        } else if ($this->matchArr['matchType']==='dateTime'){
            $dateTimeObj = new \SourcePot\Match\DateTime();
            $dateTimeObj->set($this->matchArr['value']);
            $this->matchArr['match']=$dateTimeObj->match($toMatchValue);
        }
        return $this->matchArr['match']??0;
    }

}
?>