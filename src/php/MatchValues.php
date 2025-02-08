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

    private const MATCH_TYPES=['strpos'=>'Contains','stripos'=>'Contains (ci)','matchInt'=>'Integer match','matchFloat'=>'Float match','stringChunks'=>'String chunk match','unycom'=>'UNYCOM case','dateTime'=>'DateTime'];
    
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
 
    final public function match($toMatchValue):float|int
    {
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
        } else if ($this->matchArr['matchType']==='matchInt'){
            $this->matchArr['match']=$this->numberMatch($this->matchArr['value'],$toMatchValue,$this->matchArr['matchType']);
        } else if ($this->matchArr['matchType']==='matchFloat'){
            $this->matchArr['match']=$this->numberMatch($this->matchArr['value'],$toMatchValue,$this->matchArr['matchType']);
        } else if ($this->matchArr['matchType']==='stringChunks'){
            $this->matchArr['match']=$this->stringChunksMatch($this->matchArr['value'],$toMatchValue);
        } else if ($this->matchArr['matchType']==='unycom'){
            $unycomObj = new \SourcePot\Match\UNYCOM();
            $unycomObj->set($this->matchArr['value']);
            if ($unycomObj->isValid()){
                $this->matchArr['match']=$unycomObj->match($toMatchValue);
            } else {
                $this->matchArr['match']=0;
            }
        } else if ($this->matchArr['matchType']==='dateTime'){
            $dateTimeObj = new \SourcePot\Match\DateTime();
            $dateTimeObj->set($this->matchArr['value']);
            $this->matchArr['match']=$dateTimeObj->match($toMatchValue);
        }
        return $this->matchArr['match']??0;
    }

    private function stringChunksMatch($stringA,$stringB):float|int
    {
        $pattern='/[\{\}\[\]\(\)\'";,|\/\\\]+/';
        if (mb_strlen($stringA)>mb_strlen($stringB)){
            $testString=$stringB;
            $chunks=preg_split($pattern,$stringA);
        } else {
            $testString=$stringA;
            $chunks=preg_split($pattern,$stringB);
        }
        $matchCount=$count=0;
        foreach($chunks as $chunk){
            if (empty($chunk)){continue;}
            if (mb_strpos($testString,$chunk)===FALSE){$matchCount++;}
            $count++;
        }
        return 1-$matchCount/$count;
    }

    private function numberMatch($valA,$valB,$type='matchInt'):float|int
    {
        if ($valA===$valB){return 1;}
        $helperObj = new Helper();
        $valA=$helperObj->string2number($valA);
        $valB=$helperObj->string2number($valB);
        if ($valA===$valB){return 1;}
        $valA=floatval($valA);
        $valB=floatval($valB);
        if ($type==='matchInt'){
            $valA=intval(round($valA));
            $valB=intval(round($valB));
        }
        return (($valA>$valB)?$valB:$valA)/(($valA>$valB)?$valA:$valB);
    }

}
?>