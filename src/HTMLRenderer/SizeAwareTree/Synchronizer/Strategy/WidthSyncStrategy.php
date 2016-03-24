<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy;

use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Element;

/**
 * Sets width of an element. 
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class WidthSyncStrategy extends DimensionSyncStrategy
{
    protected function getDimensionOf(Element $e)
    {
        return $e->getWidth();
    }
    
    protected function setNewDimensionOf(Element $e, $newDimension)
    {
        $e->setWidth($newDimension);
    }
}
