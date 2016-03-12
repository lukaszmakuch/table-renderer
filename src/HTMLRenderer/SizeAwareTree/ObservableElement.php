<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree;

abstract class ObservableElement implements Element
{
    private $observers;
    
    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }

    public function observeBy(ElementObserver $observer)
    {
        $this->observers->attach($observer);
    }
    
    protected function notifyObservers()
    {
        /* @var $singleObserver ElementObserver */
        foreach ($this->observers as $singleObserver) {
            $singleObserver->noticeChangeOf($this);
        }
    }
}
