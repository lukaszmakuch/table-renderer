<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer;

/**
 * Holds plain text.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class TextValue extends AtomicCellValue
{
    private $itsText;
    
    /**
     * @param String $itsText held text value
     */
    public function __construct($itsText)
    {
        $this->itsText = $itsText;
    }
    
    /**
     * @return String held text value
     */
    public function getText()
    {
        return $this->itsText;
    }
}
