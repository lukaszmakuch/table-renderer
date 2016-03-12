<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\FlatGridBuilder;

use lukaszmakuch\TableRenderer\HTMLRenderer\FlatGrid\FlatGrid;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\AtomicValue;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\HorizontalContainerFactory;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Factory\SynchronizerFactoryImpl;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\HeightSyncStrategy;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\WidthSyncStrategy;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\VerticalContainerFactory;
use PHPUnit_Framework_TestCase;

class FlatGridBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var VerticalContainerFactory
     */
    private $verticalContainerF;
    
    /**
     * @var HorizontalContainerFactory
     */
    private $horizontalContainerF;
    
    protected function setUp()
    {
        $this->verticalContainerF = new VerticalContainerFactory(
            new SynchronizerFactoryImpl(
                new HeightSyncStrategy()
            )
        );
        $this->horizontalContainerF = new HorizontalContainerFactory(
            new SynchronizerFactoryImpl(
                new WidthSyncStrategy()
            )
        );
    }

    public function testBuildingFlatGrid()
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
        
        $tree = $this->verticalContainerF->buildContainer();
        
        $abColumn = $this->horizontalContainerF->buildContainer();
        $a = new AtomicValue("a");
        $b = new AtomicValue("b");
        $abColumn->add($a);
        $abColumn->add($b);
        
        $xyzColumn = $this->horizontalContainerF->buildContainer();
        $x = new AtomicValue("x");
        $y = new AtomicValue("y");
        $z = new AtomicValue("z");
        $xyzColumn->add($x);
        $xyzColumn->add($y);
        $xyzColumn->add($z);
        
        $tree->add($abColumn);
        $tree->add($xyzColumn);
        
        $gridBuilder = new FlatGridBuilder();
        $tree->accept($gridBuilder);
        $grid = $gridBuilder->getBuiltGrid();
        $this->assertValueHolderProperties($grid, "a", 0, 0, 1, 3);
        $this->assertValueHolderProperties($grid, "b", 0, 3, 1, 3);
        $this->assertValueHolderProperties($grid, "x", 1, 0, 1, 2);
        $this->assertValueHolderProperties($grid, "y", 1, 2, 1, 2);
        $this->assertValueHolderProperties($grid, "z", 1, 4, 1, 2);
    }
    
    private function assertValueHolderProperties(FlatGrid $grid, $storedValue, $posX, $posY, $width, $height)
    {
        $this->assertTrue($storedValue == $grid->getValueHolderAt($posX, $posY)->getHeldValue());
        for ($colIndex = $posX; $colIndex < ($posX + $width); $colIndex++) {
            for ($rowIndex = $posY; $rowIndex < ($posY + $height); $rowIndex++) {
                $this->assertEquals(
                    (($colIndex == $posX) and ($rowIndex == $posY)),
                    $grid->hasValueHolderAt($colIndex, $rowIndex)
                );
            }
        }
    }
}
