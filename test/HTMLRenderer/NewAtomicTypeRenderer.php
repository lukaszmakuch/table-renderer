<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer;

class NewAtomicTypeRenderer implements AtomicValueRenderer\AtomicValueRenderer
{
    public function render(\lukaszmakuch\TableRenderer\AtomicCellValue $value)
    {
        /* @var $value \lukaszmakuch\TableRenderer\NewAtomicType */
        return new \DOMText($value->itsValue);
    }
}
