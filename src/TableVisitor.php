<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer;

/**
 * Visits horizontal and vertical containers and all atomic values together.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface TableVisitor
{
    public function visitAtomicCellValue(AtomicCellValue $atomicCellValue);
    public function visitHorizontalContainer(HorizontalContainer $horizontalContainer);
    public function visitVerticalContainer(VerticalContainer $verticalContainer);
}
