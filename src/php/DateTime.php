<?php
/*
* @package Match
* @author Carsten Wallenhauer <admin@datapool.info>
* @copyright 2024 to today Carsten Wallenhauer
* @license https://opensource.org/license/mit MIT
*/

declare(strict_types=1);

namespace SourcePot\Match;

final class DateTime{

    
    private $dateTimeParser=NULL;

    function __construct()
    {
        $this->dateTimeParser=new \SourcePot\Asset\DateTimeParser();
    }

    /**
     * Getter methods
     */

    final public function __toString():string
    {
        $dateTime=$this->dateTimeParser->get();
        return $dateTime->format('c');
    }

    final public function get():\DateTime
    {
        return $this->dateTimeParser->get();
    }
    
    final public function isValid():bool
    {
        return $this->dateTimeParser->isValid();
    }
    
    /**
     * Setter methods
     */

    final function set($value)
    {

        $this->dateTimeParser->set($value);
    }

    /**
     * Feature methods
     */

    final public function match($value):float|int
    {
        $timestampA=$this->dateTimeParser->get()->getTimestamp();
        $this->dateTimeParser->set($value);
        if (!$this->dateTimeParser->isValid()){return 0;}
        $timestampB=$this->dateTimeParser->get()->getTimestamp();
        if ($timestampA===$timestampB){
            return 1;
        } else {
            return 0;
        }
    }

}
?>            