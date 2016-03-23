<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTreeBuilder;

use lukaszmakuch\TableRenderer\HorizontalContainer;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\AtomicValue;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\HorizontalContainer as SizeAwareHorizontalContainer;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\HorizontalContainerFactory;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Factory\SynchronizerFactoryImpl;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\HeightSyncStrategy;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\WidthSyncStrategy;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\VerticalContainer as SizeAwareVerticalContainer;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\VerticalContainerFactory;
use lukaszmakuch\TableRenderer\TextValue;
use lukaszmakuch\TableRenderer\VerticalContainer;
use PHPUnit_Framework_TestCase;

class SizeAwareTreeBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SizeAwareTreeBuilder
     */
    private $builder;

    protected function setUp()
    {
        $this->builder = new SizeAwareTreeBuilder();
    }
    
    public function testBuildingTree()
    {
        /**
         * ____
         * |a|x|
         * | |_|
         * |_|y|
         * | |_|
         * |b| |
         * | |z|
         * |_|_|
         */
        
        $a = new TextValue("a");
        $b = new TextValue("b");
        
        $x = new TextValue("x");
        $y = new TextValue("y");
        $z = new TextValue("z");
        
        $abColumn = new HorizontalContainer();
        $abColumn->add($a);
        $abColumn->add($b);
        
        $xyzColumn = new HorizontalContainer();
        $xyzColumn->add($x);
        $xyzColumn->add($y);
        $xyzColumn->add($z);
        
        $table = (new VerticalContainer())
            ->add($abColumn)
            ->add($xyzColumn);
        
        $table->accept($this->builder);
        
        /* @var $sizeAwareTree VerticalContainer */
        $sizeAwareTree = $this->builder->getBuiltSizeAwareTree();
        
        $this->assertInstanceOf(SizeAwareVerticalContainer::class, $sizeAwareTree);
        $fetchedTableColumns = $sizeAwareTree->getElements();
        $this->assertCount(2, $fetchedTableColumns);
        /* @var $fetchedAbColumn SizeAwareHorizontalContainer */
        $fetchedAbColumn = $fetchedTableColumns[0];
        /* @var $fetchedXyzColumn SizeAwareHorizontalContainer */
        $fetchedXyzColumn = $fetchedTableColumns[1];
        $this->assertInstanceOf(SizeAwareHorizontalContainer::class, $fetchedAbColumn);
        $this->assertInstanceOf(SizeAwareHorizontalContainer::class, $fetchedXyzColumn);
        
        $fetchedAbColumnElements = $fetchedAbColumn->getElements();
        $this->assertCount(2, $fetchedAbColumnElements);
        /* @var $fetchedValueA AtomicValue */
        $fetchedValueA = $fetchedAbColumnElements[0];
        /* @var $fetchedValueB AtomicValue */
        $fetchedValueB = $fetchedAbColumnElements[1];
        
        $fetchedXyzColumnElements = $fetchedXyzColumn->getElements();
        $this->assertCount(3, $fetchedXyzColumnElements);
        /* @var $fetchedValueX AtomicValue */
        $fetchedValueX = $fetchedXyzColumnElements[0];
        /* @var $fetchedValueY AtomicValue */
        $fetchedValueY = $fetchedXyzColumnElements[1];
        /* @var $fetchedValueZ AtomicValue */
        $fetchedValueZ = $fetchedXyzColumnElements[2];
        
        $this->assertTrue($a === $fetchedValueA->getHeldValue());
        $this->assertTrue($b === $fetchedValueB->getHeldValue());
        $this->assertTrue($x === $fetchedValueX->getHeldValue());
        $this->assertTrue($y === $fetchedValueY->getHeldValue());
        $this->assertTrue($z === $fetchedValueZ->getHeldValue());
    }
    
    public function testExceptionWhenNothingBuiltYet()
    {
        $this->setExpectedException(Exception\NothingBuiltYet::class);
        $this->builder->getBuiltSizeAwareTree();
    }
}