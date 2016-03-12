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
     * @var TableElement[]
     */
    private $elements = [];
    
    /**
     * Adds a new element to the container.
     * 
     * @param TableElement $element
     * 
     * @return Container self
     */
    public function add(TableElement $element)
    {
        $this->elements[] = $element;
        return $this;
    }
    
    /**
     * @return TableElement[] all of its elements
     */
    public function getElements()
    {
        return $this->elements;
    }
}
