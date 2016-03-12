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
    private $e1;
    private $e2;
    private $synchronizingStrategy;
    
    public function __construct(Element $e1, Element $e2, SynchronizingStrategy $strategy)
    {
        $this->e1 = $e1;
        $this->e2 = $e2;
        $this->synchronizingStrategy = $strategy;
    }
    
    public function start()
    {
        $this->e1->observeBy($this);
        $this->e2->observeBy($this);
        $this->noticeChangeOf($this->e1);
        $this->noticeChangeOf($this->e2);
    }
    
    public function noticeChangeOf(Element $e)
    {
        $this->synchronizingStrategy->synchronize($this->e1, $this->e2);
    }
}
