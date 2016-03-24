<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree;

/**
 * An element of any type.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface Element
{
    /**
     * @return int
     */
    public function getWidth();
    
    /**
     * @return int
     */
    public function getHeight();
    
    /**
     * @param int $width
     */
    public function setWidth($width);
    
    /**
     * @param int $height
     */
    public function setHeight($height);
    
    public function observeBy(ElementObserver $observer);
    
    public function accept(TreeVisitor $visitor);
}
