<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\FlatGrid;

/**
 * Represents a two dimensional grid (ready to be used to render a html table).
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class FlatGrid
{
    private $width = 0;
    private $height = 0;
    private $valueHolders = [];
    
    /**
     * Stores some value with the given width and height starting
     * at some position at the x axis and some position at the y axis.
     * 
     * @param mixed $valueHolder value holder to associate with position
     * @param int $xPos
     * @param int $yPos
     * 
     * @return null
     */
    public function addValueHolder(ValueHolder $valueHolder, $xPos, $yPos)
    {
        if (!isset($this->valueHolders[$yPos])) {
            $this->valueHolders[$yPos] = [];
        }
        
        $this->valueHolders[$yPos][$xPos] = $valueHolder;
        
        $this->width = max([
            ($xPos + $valueHolder->getWidth()),
            $this->width
        ]);
        
        $this->height = max([
            ($yPos + $valueHolder->getHeight()),
            $this->height
        ]);
    }
    
    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }
    
    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $x
     * @param int $y
     * 
     * @return ValueHolder
     */
    public function getValueHolderAt($x, $y)
    {
        if (!$this->hasValueHolderAt($x, $y)) {
            throw new Exception\ValueNotFound();
        }
        
        return $this->valueHolders[$y][$x];
    }
    
    /**
     * @param int $x
     * @param int $y
     * 
     * @return boolean
     */
    public function hasValueHolderAt($x, $y)
    {
        return isset($this->valueHolders[$y][$x]);
    }
}
