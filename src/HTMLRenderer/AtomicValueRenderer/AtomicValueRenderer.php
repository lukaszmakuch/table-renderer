<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\AtomicValueRenderer;

use DOMNode;
use lukaszmakuch\TableRenderer\AtomicCellValue;
use lukaszmakuch\TableRenderer\HTMLRenderer\AtomicValueRenderer\Exception\UnableToRender;

/**
 * Somehow transform an AtomicCellValue into a DOMNode.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface AtomicValueRenderer
{
    /**
     * Renders node which should be put inside a td tag.
     * 
     * @param AtomicCellValue $value
     * 
     * @return DOMNode
     * @throws UnableToRender
     */
    public function render(AtomicCellValue $value);
}
