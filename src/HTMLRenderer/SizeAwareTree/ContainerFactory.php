<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree;

abstract class ContainerFactory
{
    protected $synchronizerFactory;
    
    public function __construct($synchronizerFactory)
    {
        $this->synchronizerFactory = $synchronizerFactory;
    }
    
    /**
     * @return Container
     */
    public abstract function buildContainer();
}