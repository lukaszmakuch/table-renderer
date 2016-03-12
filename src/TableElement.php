<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer;

/**
 * Any part of a table.
 * 
 * When the table contains just one element, 
 * it may be the whole table.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface TableElement
{
    /**
     * @param TableVisitor $visitor
     * 
     * @return null
     */
    public function accept(TableVisitor $visitor);
}
