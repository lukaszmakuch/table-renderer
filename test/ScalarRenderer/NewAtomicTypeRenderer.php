<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\ScalarRenderer;

use lukaszmakuch\TableRenderer\NewAtomicType;
use lukaszmakuch\TableRenderer\TableElement;

class NewAtomicTypeRenderer implements ScalarRenderer
{
    public function getScalarRepresentationOf(TableElement $table)
    {
        /* @var $table NewAtomicType */
        return [
            "type" => "new-atomic-type",
            "value" => $table->itsValue
        ];
    }
}
