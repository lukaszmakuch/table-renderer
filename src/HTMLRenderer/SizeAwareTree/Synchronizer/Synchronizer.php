<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer;

use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Element;

/**
 * Somehow syncrhonizes two elements.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface Synchronizer
{
    /**
     * @return null
     */
    public function synchronize(Element $e1, Element $e2);
}
