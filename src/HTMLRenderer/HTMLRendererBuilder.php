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
use lukaszmakuch\TableRenderer\HTMLRenderer\AtomicValueRenderer\TextRenderer;
use lukaszmakuch\TableRenderer\HTMLRenderer\FlatGridBuilder\FlatGridBuilder;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\HorizontalContainerFactory;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Factory\SynchronizerFactoryImpl;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\HeightSyncStrategy;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\WidthSyncStrategy;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\VerticalContainerFactory;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTreeBuilder\SizeAwareTreeBuilder;

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

    public function __construct()
    {
        $this->attributeContainer = new ObjectAttributeContainerImpl();
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
            new TextRenderer(),
            $this->attributeContainer
        );
    }
}