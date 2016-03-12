<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTreeBuilder;

use lukaszmakuch\TableRenderer\AtomicCellValue;
use lukaszmakuch\TableRenderer\Container;
use lukaszmakuch\TableRenderer\HorizontalContainer;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\AtomicValue;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\ContainerFactory;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Element;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTreeBuilder\Exception\NothingBuiltYet;
use lukaszmakuch\TableRenderer\TableElement;
use lukaszmakuch\TableRenderer\TableVisitor;
use lukaszmakuch\TableRenderer\VerticalContainer;

class SizeAwareTreeBuilder implements TableVisitor
{
    private $verticalContainerFactory;
    private $horizontalContainerFactory;
    private $builtSizeAwareTree;
    
    public function __clone()
    {
        $this->builtSizeAwareTree = null;
    }
    
    public function __construct(
        ContainerFactory $verticalCompositeFactory,
        ContainerFactory $horizontalCompositeFactory
    ) {
        $this->verticalContainerFactory = $verticalCompositeFactory;
        $this->horizontalContainerFactory = $horizontalCompositeFactory;
    }

    /**
     * @return Element
     * @throws NothingBuiltYet
     */
    public function getBuiltSizeAwareTree()
    {
        if (is_null($this->builtSizeAwareTree)) {
            throw new NothingBuiltYet();
        }
        
        return $this->builtSizeAwareTree;
    }
    
    public function visitAtomicCellValue(AtomicCellValue $atomicCellValue)
    {
        $this->builtSizeAwareTree = new AtomicValue($atomicCellValue);
    }

    public function visitHorizontalContainer(HorizontalContainer $horizontalContainer)
    {
        $this->buildSizeAwareContainerFrom($this->horizontalContainerFactory, $horizontalContainer);
    }

    public function visitVerticalContainer(VerticalContainer $verticalContainer)
    {
        $this->buildSizeAwareContainerFrom($this->verticalContainerFactory, $verticalContainer);
    }
    
    /**
     * Fills $this->builtSizeAwareTree with a new size aware container with content
     * created based on the given container.
     * 
     * @param ContainerFactory $containerFactory
     * @param Container $sourceContainer
     * 
     * @return null
     */
    private function buildSizeAwareContainerFrom(
        ContainerFactory $containerFactory, 
        Container $sourceContainer
    ) {
        $this->builtSizeAwareTree = $containerFactory->buildContainer();
        foreach ($sourceContainer->getElements() as $elementtoAdd) {
            $this->builtSizeAwareTree->add($this->getSizeAwareTreeOf($elementtoAdd));
        }
    }
    
    /**
     * @param TableElement $tableElement
     * @return Element
     */
    private function getSizeAwareTreeOf(TableElement $tableElement)
    {
        $builder = clone $this;
        $tableElement->accept($builder);
        return $builder->getBuiltSizeAwareTree();
    }
}
