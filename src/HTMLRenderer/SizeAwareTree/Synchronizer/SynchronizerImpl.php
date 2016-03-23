<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer;

use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Element;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\SynchronizingStrategy;

class SynchronizerImpl implements Synchronizer
{
    private $syncStrategy;
    
    public function __construct(SynchronizingStrategy $syncStrategy)
    {
        $this->syncStrategy = $syncStrategy;
    }
    
    public function synchronize(Element $e1, Element $e2)
    {
        (new Watcher($e1, $e2, $this->syncStrategy))->start();
    }
}
