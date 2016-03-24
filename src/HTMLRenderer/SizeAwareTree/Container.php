<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree;

/**
 * An element which may contain other elements.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
abstract class Container extends ObservableElement
{
    /**
     * @var Element[]
     */
    protected $elements;
    
    public function __construct()
    {
        parent::__construct();
        $this->elements = [];
    }
    
    /**
     * @return null
     */
    public function add(Element $element)
    {
        $this->elements[] = $element;
    }
    
    /**
     * @return Element[]
     */
    public function getElements()
    {
        return $this->elements;
    }
}
