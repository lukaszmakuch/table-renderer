<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\ScalarRenderer\Impl;

use lukaszmakuch\TableRenderer\ScalarRenderer\ScalarRenderer;
use lukaszmakuch\TableRenderer\TableElement;
use lukaszmakuch\TableRenderer\TextValue;

/**
 * Delegates work to proper objects based on the class of given object.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class TextRenderer implements ScalarRenderer
{
    public function getScalarRepresentationOf(TableElement $table)
    {
        /* @var $table TextValue */
        return [
            "type" => "text",
            "value" => $table->getText()
        ];
    }
}
