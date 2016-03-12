<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree;

class AtomicValue extends ObservableElement
{
    private $heldValue;
    private $width;
    private $height;
    
    public function __construct($heldValue)
    {
        parent::__construct();
        $this->heldValue = $heldValue;
        $this->width = 1;
        $this->height = 1;
    }
    
    public function getHeldValue()
    {
        return $this->heldValue;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setHeight($height)
    {
        $this->height = $height;
        $this->notifyObservers();
    }

    public function setWidth($width)
    {
        $this->width = $width;
        $this->notifyObservers();
    }
    
    public function accept(TreeVisitor $visitor)
    {
        $visitor->visitAtomicValue($this);
    }
}
