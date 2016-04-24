<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\ScalarRenderer\Impl;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\ClassBasedRegistry\Exception\ValueNotFound;
use lukaszmakuch\TableRenderer\ScalarRenderer\Exception\UnableToGetScalarRepresentation;
use lukaszmakuch\TableRenderer\ScalarRenderer\ScalarRenderer;
use lukaszmakuch\TableRenderer\TableElement;

/**
 * Delegates work to proper objects based on the class of given object.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ClassBasedProxy implements ScalarRenderer
{
    /**
     * @var ClassBasedRegistry
     */
    private $renderersByClasses;

    public function __construct()
    {
        $this->renderersByClasses = new ClassBasedRegistry();
    }

    public function registerRenderer(
        $classOfSupportedObjects,
        ScalarRenderer $actualRenderer
    ) {
        $this->renderersByClasses->associateValueWithClasses(
            $actualRenderer,
            [$classOfSupportedObjects]
        );
    }

    public function getScalarRepresentationOf(TableElement $table)
    {
        try {
        return $this->renderersByClasses
            ->fetchValueByObjects([$table])
            ->getScalarRepresentationOf($table);
        } catch (ValueNotFound $e) {
            throw new UnableToGetScalarRepresentation();
        }
    }
}
