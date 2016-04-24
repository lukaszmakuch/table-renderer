<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer;

class NewAtomicType extends AtomicCellValue
{
    public $itsValue;
    
    public function __construct($itsValue)
    {
        $this->itsValue = $itsValue;
    }
}
