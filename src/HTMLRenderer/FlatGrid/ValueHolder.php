<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\FlatGrid;

/**
 * Holds information about width and height of a value.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ValueHolder
{
    private $heldValue;
    private $width;
    private $height;
    
    /**
     * @param mixed $heldValue
     * @param int $width
     * @param int $height
     */
    public function __construct($heldValue, $width, $height)
    {
        $this->heldValue = $heldValue;
        $this->width = $width;
        $this->height = $height;
    }
    
    /**
     * @return mixed
     */
    public function getHeldValue()
    {
        return $this->heldValue;
    }
    
    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }
    
    /**
     * 
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }
}