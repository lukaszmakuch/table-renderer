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
 * Somehow performs synchronization of two elements.
 */
interface SynchronizingStrategy
{
    public function synchronize(Element $e1, Element $e2);
}
