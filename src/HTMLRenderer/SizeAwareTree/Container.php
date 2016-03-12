<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree;

use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Factory\SynchronizerFactory;

abstract class Container extends ObservableElement
{
    /**
     * @var Element[]
     */
    protected $elements;
    private $synchronizerFactory;
    
    public function __construct(SynchronizerFactory $synchronizerF)
    {
        parent::__construct();
        $this->elements = [];
        $this->synchronizerFactory = $synchronizerF;
    }
    
    /**
     * @return null
     */
    public function add(Element $element)
    {
        $this->elements[] = $element;
        $synchronizer = $this->synchronizerFactory->getSynchronizerOf($this, $element);
        $synchronizer->start();
        $this->notifyObservers();
    }
    
    /**
     * @return Element[]
     */
    public function getElements()
    {
        return $this->elements;
    }
}
