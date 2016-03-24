<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree;

/**
 * Visits tree structures.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface TreeVisitor
{
    public function visitAtomicValue(AtomicValue $value);
    public function visitVerticalContainer(VerticalContainer $container);
    public function visitHorizontalContainer(HorizontalContainer $container);
}
