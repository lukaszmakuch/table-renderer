<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\FlatGridBuilder;

use lukaszmakuch\TableRenderer\HTMLRenderer\FlatGrid\FlatGrid;
use PHPUnit_Framework_TestCase;

class FlatGridBuilderTest extends PHPUnit_Framework_TestCase
{
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
        
        $tree = 
            (new \lukaszmakuch\TableRenderer\VerticalContainer())
                ->add((new \lukaszmakuch\TableRenderer\HorizontalContainer())
                    ->add(new \lukaszmakuch\TableRenderer\TextValue("a"))
                    ->add(new \lukaszmakuch\TableRenderer\TextValue("b"))
                )
                ->add((new \lukaszmakuch\TableRenderer\HorizontalContainer())
                    ->add(new \lukaszmakuch\TableRenderer\TextValue("x"))
                    ->add(new \lukaszmakuch\TableRenderer\TextValue("y"))
                    ->add(new \lukaszmakuch\TableRenderer\TextValue("z"))
                )
        ;
        
        $sizeAwareTreeBuilder = new \lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTreeBuilder\SizeAwareTreeBuilder();
        $tree->accept($sizeAwareTreeBuilder);
        $sizeAwareTree = $sizeAwareTreeBuilder->getBuiltSizeAwareTree();
        $gridBuilder = new FlatGridBuilder();
        $sizeAwareTree->accept($gridBuilder);
        $grid = $gridBuilder->getBuiltGrid();
        $this->assertValueHolderProperties($grid, "a", 0, 0, 1, 3);
        $this->assertValueHolderProperties($grid, "b", 0, 3, 1, 3);
        $this->assertValueHolderProperties($grid, "x", 1, 0, 1, 2);
        $this->assertValueHolderProperties($grid, "y", 1, 2, 1, 2);
        $this->assertValueHolderProperties($grid, "z", 1, 4, 1, 2);
    }
    
    private function assertValueHolderProperties(FlatGrid $grid, $storedValue, $posX, $posY, $width, $height)
    {
        $this->assertTrue($storedValue == $grid->getValueHolderAt($posX, $posY)->getHeldValue()->getText());
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
