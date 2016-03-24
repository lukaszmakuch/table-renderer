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
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Container as SizeAwareContainer;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Element;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\HorizontalContainer as SizeAwareHorizontalContainer;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Installer\GridSynchronizerInstaller;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Installer\StickyCellsSynchronizerInstaller;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\HeightSyncStrategy;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\WidthSyncStrategy;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\SynchronizerImpl;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\TreeVisitor;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\VerticalContainer as SizeAwareVerticalContainer;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTreeBuilder\Exception\NothingBuiltYet;
use lukaszmakuch\TableRenderer\TableElement;
use lukaszmakuch\TableRenderer\TableVisitor;
use lukaszmakuch\TableRenderer\VerticalContainer;

/**
 * Builds a size aware tree which represents a structure of any table tree structure.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class SizeAwareTreeBuilder implements TableVisitor
{
    private $builtSizeAwareTree;
    
    /**
     * @var TreeVisitor[]
     */
    private $installersOfSynchronizers;
    
    public function __clone()
    {
        $this->builtSizeAwareTree = null;
    }
    
    public function __construct() {
        $widthSynchronizer = new SynchronizerImpl(new WidthSyncStrategy());
        $heightSynchronizer = new SynchronizerImpl(new HeightSyncStrategy());
        
        $this->installersOfSynchronizers = [];
        $this->installersOfSynchronizers[] = new StickyCellsSynchronizerInstaller($widthSynchronizer, $heightSynchronizer);
        $this->installersOfSynchronizers[] = new GridSynchronizerInstaller($widthSynchronizer, $heightSynchronizer);
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
        $this->buildSizeAwareContainerFrom(new SizeAwareHorizontalContainer(), $horizontalContainer);
    }

    public function visitVerticalContainer(VerticalContainer $verticalContainer)
    {
        $this->buildSizeAwareContainerFrom(new SizeAwareVerticalContainer(), $verticalContainer);
    }
    
    /**
     * Fills $this->builtSizeAwareTree with a new size aware container with content
     * created based on the given container.
     * 
     * @param SizeAwareContainer $newContainer
     * @param Container $sourceContainer
     * 
     * @return null
     */
    private function buildSizeAwareContainerFrom(
        SizeAwareContainer $newContainer, 
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
