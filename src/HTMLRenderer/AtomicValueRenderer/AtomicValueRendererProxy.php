<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\AtomicValueRenderer;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\TableRenderer\AtomicCellValue;
use lukaszmakuch\TableRenderer\HTMLRenderer\AtomicValueRenderer\Exception\UnableToRender;
use lukaszmakuch\ClassBasedRegistry\Exception\ValueNotFound;

/**
 * Hides many actual renderers behind a common interface.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class AtomicValueRendererProxy implements AtomicValueRenderer
{
    /**
     * @var ClassBasedRegistry holds renderers
     * by classes they are able to render
     */
    private $actualRenderers;
    
    public function __construct()
    {
        $this->actualRenderers = new ClassBasedRegistry();
    }
    
    /**
     * @param AtomicValueRenderer $renderer
     * @param String $supportedClassOfAtomicValues
     * 
     * @return null
     */
    public function registerRenderer(
        AtomicValueRenderer $renderer,
        $supportedClassOfAtomicValues
    ) {
        $this->actualRenderers->associateValueWithClasses(
            $renderer, 
            [$supportedClassOfAtomicValues]
        );
    }
    
    public function render(AtomicCellValue $value)
    {
        return $this->getRendererOf($value)->render($value);
    }
    
    /**
     * @param AtomicCellValue $value
     * @return AtomicValueRenderer
     * @throws UnableToRender when no renderer is found
     */
    private function getRendererOf(AtomicCellValue $value)
    {
        try {
            return $this->actualRenderers->fetchValueByObjects([$value]);
        } catch (ValueNotFound $e) {
            throw new UnableToRender();
        }
    }
}
