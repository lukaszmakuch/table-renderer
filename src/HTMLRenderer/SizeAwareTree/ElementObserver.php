<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree;

/**
 * Notices changes of an element.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ElementObserver
{
    /**
     * Triggered when that element changes in some way.
     * 
     * @param Element $e
     * 
     * @return null
     */
    public function noticeChangeOf(Element $e);
}
