<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer;

use lukaszmakuch\ObjectAttributeContainer\Impl\ObjectAttributeContainerImpl;
use lukaszmakuch\ObjectAttributeContainer\ObjectAttributeContainer;
use lukaszmakuch\TableRenderer\HTMLRenderer\AtomicValueRenderer\AtomicValueRenderer;
use lukaszmakuch\TableRenderer\HTMLRenderer\AtomicValueRenderer\AtomicValueRendererProxy;
use lukaszmakuch\TableRenderer\HTMLRenderer\AtomicValueRenderer\TextRenderer;
use lukaszmakuch\TableRenderer\HTMLRenderer\FlatGridBuilder\FlatGridBuilder;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\HorizontalContainerFactory;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Factory\SynchronizerFactoryImpl;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\HeightSyncStrategy;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\WidthSyncStrategy;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\VerticalContainerFactory;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTreeBuilder\SizeAwareTreeBuilder;
use lukaszmakuch\TableRenderer\TextValue;
/**
 * Builds HTML renderer.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class HTMLRendererBuilder
{
    /**
     * @var ObjectAttributeContainer
     */
    private $attributeContainer;
    
    /**
     * @var AtomicValueRendererProxy
     */
    private $atomicValueRendererProxy;
    
    public function __construct()
    {
        $this->attributeContainer = new ObjectAttributeContainerImpl();
        $this->atomicValueRendererProxy = new AtomicValueRendererProxy();
        $this->atomicValueRendererProxy->registerRenderer(
            new TextRenderer(), 
            TextValue::class
        );
    }
    
    /**
     * Sets source of attributes.
     * 
     * @param ObjectAttributeContainer $attributeContainer
     * 
     * @return HTMLRendererBuilder self
     */
    public function setAttributeContainer(ObjectAttributeContainer $attributeContainer)
    {
        $this->attributeContainer = $attributeContainer;
    }
    
    /**
     * Allows to extend supported atomic cell values.
     * 
     * @param String $classOfAtomicValue
     * @param AtomicValueRenderer $itsRenderer
     * 
     * @return null
     */
    public function addAtomicValueRenderer($classOfAtomicValue, AtomicValueRenderer $itsRenderer)
    {
        $this->atomicValueRendererProxy->registerRenderer($itsRenderer, $classOfAtomicValue);
    }
    
    /**
     * @return HTMLRenderer
     */
    public function buildRenderer()
    {
        return new HTMLRenderer(
            new SizeAwareTreeBuilder(
                new VerticalContainerFactory(
                    new SynchronizerFactoryImpl(
                        new HeightSyncStrategy()
                    )
                ),
                new HorizontalContainerFactory(
                    new SynchronizerFactoryImpl(
                        new WidthSyncStrategy()
                    )
                )
            ),
            new FlatGridBuilder(),
            $this->atomicValueRendererProxy,
            $this->attributeContainer
        );
    }
}