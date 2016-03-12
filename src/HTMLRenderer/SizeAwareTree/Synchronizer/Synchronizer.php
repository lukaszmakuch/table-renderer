<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer;

use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\ElementObserver;

/**
 * Somehow syncrhonizes two elements.
 */
interface Synchronizer extends ElementObserver
{
    /**
     * @return null
     */
    public function start();
}
