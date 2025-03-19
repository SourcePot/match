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

    private const MATCH_TYPES=[''=>'Identical','strpos'=>'Contains','!strpos'=>'Does not contain','stripos'=>'Contains (ci)','!stripos'=>'Does not contain (ci)','correlationContains'=>'Correlation contains','correlationMatch'=>'Correlation match','matchInt'=>'Integer match','matchFloat'=>'Float match','stringChunks'=>'String chunk match (middle chunk as needle)','patent'=>'Patent case','unycom'=>'UNYCOM case','dateTime'=>'DateTime'];
    private const STRING_CHUNK_SEPARATOR_REGEX='/[\{\}\[\]\(\)\'";,|\/\\.\s]+/';
    private const DB_TIMEZONE='UTC';

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

     final function prepareMatch():string
     {
        if ($this->matchArr['matchType']==='unycom'){
            $this->matchArr['obj'] = new \SourcePot\Match\UNYCOM();
            $this->matchArr['obj']->set($this->matchArr['value']);
            $unycomArr=$this->matchArr['obj']->get();
            return '%'.$unycomArr['Number'].'%';
        } else if ($this->matchArr['matchType']==='dateTime'){
            $this->matchArr['obj'] = new \SourcePot\Match\DateTime();
            $this->matchArr['obj']->set($this->matchArr['value']);
            $dateTimeObj=$this->matchArr['obj']->get();
            $dateTimeObj->setTimezone(new \DateTimeZone(self::DB_TIMEZONE));
            return $dateTimeObj->format('Y-m-d').'%';
        } else if ($this->matchArr['matchType']==='stringChunks'){
            $chunks=preg_split(self::STRING_CHUNK_SEPARATOR_REGEX,$this->matchArr['value']);
            $chunkIndex=intdiv(count($chunks),2);
            return '%'.$chunks[$chunkIndex].'%';
        } else if ($this->matchArr['matchType']==='' || $this->matchArr['matchType']==='strpos'){
            return '%'.$this->matchArr['value'].'%';
        } else if ($this->matchArr['matchType']==='patent'){
            return '%'.$this->patentNeedle($this->matchArr['value']).'%';
        } else {
            return '%';
        }
     }

    /**
     * Setter methods
     */

    final public function set($value,string $matchType)
    {
        if (isset(self::MATCH_TYPES[$matchType])){
            $this->matchArr['obj']=NULL;
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
        if (empty($this->matchArr['matchType'])){
            $this->matchArr['match']=($this->matchArr['value']===$this->matchArr['toMatchValue'])?1:0;
        } else if ($this->matchArr['matchType']==='strpos' || $this->matchArr['matchType']==='!strpos'){
            if (mb_strlen($this->matchArr['value'])>mb_strlen($this->matchArr['toMatchValue'])){
                $this->matchArr['match']=(mb_strpos($this->matchArr['value'],$this->matchArr['toMatchValue'])===FALSE)?0:1;
            } else {
                $this->matchArr['match']=(mb_strpos($this->matchArr['toMatchValue'],$this->matchArr['value'])===FALSE)?0:1;
            }
            if ($this->matchArr['matchType']==='!strpos'){
                $this->matchArr['match']=($this->matchArr['match']===0)?1:0;
            }
        } else if ($this->matchArr['matchType']==='stripos' || $this->matchArr['matchType']==='!stripos'){
            if (mb_strlen($this->matchArr['value'])>mb_strlen($this->matchArr['toMatchValue'])){
                $this->matchArr['match']=(mb_stripos($this->matchArr['value'],$this->matchArr['toMatchValue'])===FALSE)?0:1;
            } else {
                $this->matchArr['match']=(mb_stripos($this->matchArr['toMatchValue'],$this->matchArr['value'])===FALSE)?0:1;
            }
            if ($this->matchArr['matchType']==='!stripos'){
                $this->matchArr['match']=($this->matchArr['match']===0)?1:0;
            }
        } else if ($this->matchArr['matchType']==='correlationContains' || $this->matchArr['matchType']==='correlationMatch'){
            $this->matchArr['match']=$this->correlation($this->matchArr['value'],$toMatchValue,$this->matchArr['matchType']);
        } else if ($this->matchArr['matchType']==='matchInt'){
            $this->matchArr['match']=$this->numberMatch($this->matchArr['value'],$toMatchValue,$this->matchArr['matchType']);
        } else if ($this->matchArr['matchType']==='matchFloat'){
            $this->matchArr['match']=$this->numberMatch($this->matchArr['value'],$toMatchValue,$this->matchArr['matchType']);
        } else if ($this->matchArr['matchType']==='stringChunks'){
            $this->matchArr['match']=$this->stringChunksMatch($this->matchArr['value'],$toMatchValue);
        } else if ($this->matchArr['matchType']==='patent'){
            $this->matchArr['match']=$this->patentMatch($this->matchArr['value'],$toMatchValue);
        } else if ($this->matchArr['matchType']==='unycom'){
            if ($this->matchArr['obj']->isValid()){
                $this->matchArr['match']=$this->matchArr['obj']->match($toMatchValue);
            } else {
                $this->matchArr['match']=0;
            }
        } else if ($this->matchArr['matchType']==='dateTime'){
            if ($this->matchArr['obj']->isValid()){
                $this->matchArr['match']=$this->matchArr['obj']->match($toMatchValue);
            } else {
                $this->matchArr['match']=0;
            }
        }
        return $this->matchArr['match']??0;
    }

    private function patentNeedle($string):string
    {
        preg_match('/[0-9\, \/]{3,}/',$string,$match);
        $string=preg_replace('/[^0-9]+/','',$match[0]);
        return substr($string,-3);
    }

    private function patentMatch($stringA,$stringB):float|int
    {
        // extract string A components
        preg_match_all('/([A-Z]{2})[^A-Z]/',$stringA,$matchesA);
        $matchesA=array_pop($matchesA);
        $ccA=array_pop($matchesA)??'';
        preg_match('/[0-9\, \/]{3,}/',$stringA,$matchA);
        $numberA=preg_replace('/[^0-9]+/','',$matchA[0]);
        // extract string B components
        preg_match_all('/([A-Z]{2})[^A-Z]/',$stringB,$matchesB);
        $matchesB=array_pop($matchesB);
        $ccB=array_pop($matchesB)??'';
        preg_match('/[0-9\, \/]{3,}/',$stringB,$matchB);
        $numberB=preg_replace('/[^0-9]+/','',$matchB[0]);
        // country code match
        if ($ccA == $ccB){
            $ccAmatch=1;
        } else if ((empty($ccA) && !empty($ccB)) || (!empty($ccA) && empty($ccB))){
            $ccAmatch=0.9;
        } else {
            $ccAmatch=0;
        }
        // number match
        $strLenA=strlen($numberA);
        $strLenB=strlen($numberB);
        if ($strLenA-$strLenB===2){
            if (strpos($numberA,$numberB)===FALSE){
                $numberMatch=0;
                $ccAmatch=0;
            } else {
                $numberMatch=1;
            }
        } else if ($strLenB-$strLenA===2){
            if (strpos($numberB,$numberA)===FALSE){
                $numberMatch=0;
                $ccAmatch=0;
            } else {
                $numberMatch=1;
            }
        } else if ($strLenA===$strLenB){
            if ($numberA===$numberB){
                $numberMatch=1;
            } else {
                $numberMatch=0;
                $ccAmatch=0;
            }
        } else {
            // number length mismatch other than 2
            $numberMatch=0;
            $ccAmatch=0;
        }
        return $ccAmatch*$numberMatch;
    }

    private function stringChunksMatch($stringA,$stringB):float|int
    {
        if (mb_strlen($stringA)>mb_strlen($stringB)){
            $testString=$stringB;
            $chunks=preg_split(self::STRING_CHUNK_SEPARATOR_REGEX,$stringA);
        } else {
            $testString=$stringA;
            $chunks=preg_split(self::STRING_CHUNK_SEPARATOR_REGEX,$stringB);
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
        if ($valA===FALSE || $valB===FALSE){return 0;}
        if ($valA===$valB){return 1;}
        $valA=floatval($valA);
        $valB=floatval($valB);
        if ($type==='matchInt'){
            $valA=intval(round($valA));
            $valB=intval(round($valB));
        }
        $match=(($valA>$valB)?$valB:$valA)/(($valA>$valB)?$valA:$valB);
        return ($match<0)?0:$match;
    }

    private function correlation($stringA,$stringB,$matchType):float|int
    {
        if ($stringA===$stringB){return 1;}
        $stringA=strval($stringA);
        $stringB=strval($stringB);
        $chrsA=mb_str_split($stringA);
        $chrsB=mb_str_split($stringB);
        if (count($chrsB)>count($chrsA)){
            $tmp=$chrsA;
            $chrsA=$chrsB;
            $chrsB=$tmp;
        }
        $topLikeness=0;
        for($shift=0;$shift<count($chrsA);$shift++){
            $correlation=0;
            foreach($chrsB as $indexB=>$chrB){
                $indexA=$indexB+$shift;
                if (isset($chrsA[$indexA])){
                    if ($chrsA[$indexA]===$chrB){
                        $correlation++;
                    }
                }
            }
            if ($correlation>$topLikeness){
                $topLikeness=$correlation;
            }
        }
        return $topLikeness/(($matchType==='correlationMatch')?count($chrsA):count($chrsB));
    }
    

}
?>