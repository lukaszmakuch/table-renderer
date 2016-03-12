<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy;

use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Element;
use lukaszmakuch\Math;

abstract class DimensionSyncStrategy implements SynchronizingStrategy
{
    public function synchronize(Element $e1, Element $e2)
    {
        $e1Dimension = $this->getDimensionOf($e1);
        $e2Dimension = $this->getDimensionOf($e2);
        $newDimension = Math::lcm($e1Dimension, $e2Dimension);
        if ($e1Dimension !== $newDimension) {
            $this->setNewDimensionOf($e1, $newDimension);
        }
        
        if ($e2Dimension !== $newDimension) {
            $this->setNewDimensionOf($e2, $newDimension);
        }
    }
    
    /**
     * @param Element $e
     * @return int
     */
    abstract protected function getDimensionOf(Element $e);
    
    /**
     * @param Element $e
     * @param int $newDimension
     */
    abstract protected function setNewDimensionOf(Element $e, $newDimension);
}
