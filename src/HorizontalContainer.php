<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer;

/**
 * Holds elements like that:
 * <pre>
 * a
 * b
 * c
 * </pre>
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class HorizontalContainer extends Container
{
    public function accept(TableVisitor $visitor)
    {
        $visitor->visitHorizontalContainer($this);
    }
}
