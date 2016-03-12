<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\AtomicValueRenderer;

/**
 * Renders text.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class TextRenderer implements AtomicValueRenderer
{
    public function render(\lukaszmakuch\TableRenderer\AtomicCellValue $value)
    {
        /* @var $value \lukaszmakuch\TableRenderer\TextValue */
        return new \DOMText($value->getText());
    }
}
