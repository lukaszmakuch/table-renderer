<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\AtomicValueRenderer;

use lukaszmakuch\TableRenderer\AtomicCellValue;

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
     */
    public function render(AtomicCellValue $value);
}
