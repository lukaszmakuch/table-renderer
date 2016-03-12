<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTreeBuilder\Exception;

/**
 * Thrown when accessing the result of SizeAwareTreeBuilder while nothing
 * has been built yet because nothing has been visited.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class NothingBuiltYet extends \RuntimeException
{
}