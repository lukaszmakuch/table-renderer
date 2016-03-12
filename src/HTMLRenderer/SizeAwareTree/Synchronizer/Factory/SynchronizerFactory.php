<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Factory;

use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Element;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Synchronizer;

interface SynchronizerFactory
{
    /**
     * @param Element $e1
     * @param Element $e2
     * 
     * @return Synchronizer
     */
    public function getSynchronizerOf(Element $e1, Element $e2);
}