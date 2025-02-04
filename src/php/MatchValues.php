<?php
/*
* @package Match
* @author Carsten Wallenhauer <admin@datapool.info>
* @copyright 2024 to today Carsten Wallenhauer
* @license https://opensource.org/license/mit MIT
*/

declare(strict_types=1);

namespace SourcePot\Match;

final class MatchValues{

    private const MATCH_TYPES=['strpos'=>'Contains','stripos'=>'Contains (ci)','unycom'=>'UNYCOM case'];
    
    function __construct()
    {
    }

    final public function getMatchTypes():array
    {
        return self::MATCH_TYPES;
    }

}
?>