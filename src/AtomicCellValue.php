<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer;

/**
 * Unsplittable cell value, like some text (but not a container of texts).
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
abstract class AtomicCellValue implements TableElement
{
    public function accept(TableVisitor $visitor)
    {
    }
}
