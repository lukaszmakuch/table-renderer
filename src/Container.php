<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer;

/**
 * Holds any number of table elements.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
abstract class Container implements TableElement
{
    /**
     * Adds a new element to the container.
     * 
     * @param TableElement $element
     * 
     * @return Container self
     */
    public function add(TableElement $element)
    {
    }
    
    /**
     * @return TableElement[] all of its elements
     */
    public function getElements()
    {
    }
    
    public function accept(TableVisitor $visitor)
    {
    }
}
