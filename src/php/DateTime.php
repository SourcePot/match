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

    
    private $dateTime=NULL;

    function __construct()
    {
        $this->dateTime=new \SourcePot\Asset\DateTimeParser();
    }

    /**
     * Getter methods
     */

    final public function __toString():string
    {
        $dateTime=$this->dateTime->get();
        return $dateTime->format('Y-m-d H:i:s');
    }

    final public function get():\DateTime
    {
        return $this->dateTime->get();
    }
    
    /**
     * Setter methods
     */

    final function set($value)
    {

        $this->dateTime->set($value);
    }

    /**
     * Feature methods
     */

    final public function match($value):float
    {
        // get  value A
        $dateTime=$this->dateTime->get();
        $valueA=$dateTime->format('Y-m-d H:i:s');
        // get value B
        $this->dateTime->set($value);
        $dateTime=$this->dateTime->get();
        $valueB=$dateTime->format('Y-m-d H:i:s');
        // calculate match
        if ($valueA===$valueB){
            return 1;
        } else if (strlen($valueA)>strlen($valueB)){
            return (strpos($valueA,$valueB)===FALSE)?0:0.8;
        } else if (strlen($valueA)<strlen($valueB)){
            return (strpos($valueB,$valueA)===FALSE)?0:0.8;
        } else {
            return 0;
        }
    }

}
?>            