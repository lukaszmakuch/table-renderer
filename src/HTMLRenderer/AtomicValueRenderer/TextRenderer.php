<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\AtomicValueRenderer;

use DOMText;
use lukaszmakuch\TableRenderer\AtomicCellValue;
use lukaszmakuch\TableRenderer\HTMLRenderer\AtomicValueRenderer\Exception\UnableToRender;
use lukaszmakuch\TableRenderer\TextValue;

/**
 * Renders text.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class TextRenderer implements AtomicValueRenderer
{
    public function render(AtomicCellValue $value)
    {
        if (!($value instanceof TextValue)) {
            throw new UnableToRender();
        }
        
        /* @var $value TextValue */
        return new DOMText($value->getText());
    }
}
