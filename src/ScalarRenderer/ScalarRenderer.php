<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\ScalarRenderer;

use lukaszmakuch\TableRenderer\ScalarRenderer\Exception\UnableToGetScalarRepresentation;
use lukaszmakuch\TableRenderer\TableElement;

/**
 * Renders a model of a table as a scalar value or an array of scalar values.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ScalarRenderer
{
    /**
     * @return mixed a scalar value or an array of scalar values (or arrays of them)
     * @throws UnableToGetScalarRepresentation
     */
    public function getScalarRepresentationOf(TableElement $table);
}
