<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Factory;

use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Element;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\SynchronizingStrategy;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\SynchronizerImpl;

class SynchronizerFactoryImpl implements SynchronizerFactory
{
    private $synchronizingStrategy;
    
    public function __construct(SynchronizingStrategy $syncStrategy)
    {
        $this->synchronizingStrategy = $syncStrategy;
    }

    public function getSynchronizerOf(Element $c1, Element $c2)
    {
        return new SynchronizerImpl($c1, $c2, $this->synchronizingStrategy);
    }
}
