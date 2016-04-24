<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\ScalarRenderer\Impl;

use lukaszmakuch\TableRenderer\ScalarRenderer\ScalarRenderer;
use lukaszmakuch\TableRenderer\ScalarRenderer\ScalarRendererUser;
use lukaszmakuch\TableRenderer\TableElement;

/**
 * Renders containers.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ContainerRenderer implements ScalarRenderer, ScalarRendererUser
{
    private $typeAsText;

    /**
     * @var ScalarRenderer 
     */
    private $actualRenderer;

    public function __construct($typeAsText)
    {
        $this->typeAsText = $typeAsText;
    }

    public function setScalarRenderer(ScalarRenderer $renderer)
    {
        $this->actualRenderer = $renderer;
    }

    public function getScalarRepresentationOf(TableElement $table)
    {
        /* @var $table \lukaszmakuch\TableRenderer\Container */
        return [
            "type" => $this->typeAsText,
            "value" => array_map(function (TableElement $e) {
                return $this->actualRenderer->getScalarRepresentationOf($e); 
            }, $table->getElements())
        ];
    }
}
