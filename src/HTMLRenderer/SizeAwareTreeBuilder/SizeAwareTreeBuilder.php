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
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Element;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTreeBuilder\Exception\NothingBuiltYet;
use lukaszmakuch\TableRenderer\TableElement;
use lukaszmakuch\TableRenderer\TableVisitor;
use lukaszmakuch\TableRenderer\VerticalContainer;

class SizeAwareTreeBuilder implements TableVisitor
{
    private $builtSizeAwareTree;
    
    /**
     * @var \lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\TreeVisitor[]
     */
    private $installersOfSynchronizers;
    
    public function __clone()
    {
        $this->builtSizeAwareTree = null;
    }
    
    public function __construct() {
        $widthSynchronizer = new \lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\SynchronizerImpl(new \lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\WidthSyncStrategy());
        $heightSynchronizer = new \lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\SynchronizerImpl(new \lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\HeightSyncStrategy());
        
        $this->installersOfSynchronizers = [];
        $this->installersOfSynchronizers[] = new \lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Installer\StickyCellsSynchronizerInstaller($widthSynchronizer, $heightSynchronizer);
        $this->installersOfSynchronizers[] = new \lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Installer\GridSynchronizerInstaller($widthSynchronizer, $heightSynchronizer);
    }

    /**
     * @param boolean $installSynchronizers 
     * @return Element
     * @throws NothingBuiltYet
     */
    public function getBuiltSizeAwareTree($installSynchronizers = true)
    {
        if (is_null($this->builtSizeAwareTree)) {
            throw new NothingBuiltYet();
        }
        
        if ($installSynchronizers) {
            foreach ($this->installersOfSynchronizers as $installer) {
                $this->builtSizeAwareTree->accept($installer);
            }
        }
        
        return $this->builtSizeAwareTree;
    }
    
    public function visitAtomicCellValue(AtomicCellValue $atomicCellValue)
    {
        $this->builtSizeAwareTree = new AtomicValue($atomicCellValue);
    }

    public function visitHorizontalContainer(HorizontalContainer $horizontalContainer)
    {
        $this->buildSizeAwareContainerFrom(new \lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\HorizontalContainer(), $horizontalContainer);
    }

    public function visitVerticalContainer(VerticalContainer $verticalContainer)
    {
        $this->buildSizeAwareContainerFrom(new \lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\VerticalContainer(), $verticalContainer);
    }
    
    /**
     * Fills $this->builtSizeAwareTree with a new size aware container with content
     * created based on the given container.
     * 
     * @param \lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Container $newContainer
     * @param Container $sourceContainer
     * 
     * @return null
     */
    private function buildSizeAwareContainerFrom(
        \lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Container $newContainer, 
        Container $sourceContainer
    ) {
        $this->builtSizeAwareTree = $newContainer;
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
        return $builder->getBuiltSizeAwareTree(false);
    }
}
