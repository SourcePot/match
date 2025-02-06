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

    final public function match($value):float
    {
        // get  value A
        $dateTime=$this->dateTimeParser->get();
        $dateA=$dateTime->format('Y-m-d');
        $secA=intval($dateTime->format('s'))+60*intval($dateTime->format('i'))+3600*intval($dateTime->format('H'));
        // get value B
        $this->dateTimeParser->set($value);
        $dateTime=$this->dateTimeParser->get();
        $dateB=$dateTime->format('Y-m-d');
        $secB=intval($dateTime->format('s'))+60*intval($dateTime->format('i'))+3600*intval($dateTime->format('H'));
        // calculate match
        if ($dateA===$dateB){
            if ($secA===$secB){
                return 1;
            } else {
                return 1-abs($secA-$secB)/86400;
            }
        } else {
            return 0;
        }
    }

}
?>            