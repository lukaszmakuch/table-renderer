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
 * abc
 * </pre>
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class VerticalContainer extends Container
{
    public function accept(TableVisitor $visitor)
    {
    }
}
