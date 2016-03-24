<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree;

use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Installer\GridSynchronizerInstaller;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Installer\StickyCellsSynchronizerInstaller;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\HeightSyncStrategy;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\WidthSyncStrategy;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\SynchronizerImpl;
use PHPUnit_Framework_TestCase;

class DimensionsCalculationTest extends PHPUnit_Framework_TestCase
{
    public function testHeightSync()
    {
        /**
         *  ________________           
         * |       |       |           
         * |       |       |           
         * |  a    |  x    |           
         * |_______|       |           
         * |       |       |           
         * |  b    |_______|           
         * |_______|       |           
         * |       |       |           
         * |       |  y    |           
         * |  c    |       |           
         * |_______|_______|           
         * 
         */
        
        $cellA = new AtomicValue("a");
        $cellB = new AtomicValue("b");
        $cellC = new AtomicValue("c");
        
        $colABC = new HorizontalContainer();
        $colABC->add($cellA);
        $colABC->add($cellB);
        $colABC->add($cellC);
        
        $cellX = new AtomicValue("x");
        $cellY = new AtomicValue("y");
        $colXY = new HorizontalContainer();
        $colXY->add($cellX);
        $colXY->add($cellY);
        
        $table = new VerticalContainer();
        $table->add($colABC);
        $table->add($colXY);
        
        $this->installSynchronizersTo($table);
        
        $this->assertDimensions([
            [$table, 2, 6],
            
            [$colABC, 1, 6],
            [$colXY, 1, 6],
            
            [$cellA, 1, 2],
            [$cellB, 1, 2],
            [$cellC, 1, 2],
            
            [$cellX, 1, 3],
            [$cellY, 1, 3],
        ]);
    }

    public function testWidthSync()
    {
        /**
         *  _______________________  
         * |          |           |  
         * |    a     |      b    |  
         * |          |           |  
         * |__________|___________|  
         * |      |       |       |  
         * |  x   |   y   |  z    |  
         * |______|_______|_______|  
         *                           
         */
    
        
        $cellA = new AtomicValue("a");
        $cellB = new AtomicValue("b");
        
        $rowAB = new VerticalContainer();
        $rowAB->add($cellA);
        $rowAB->add($cellB);
        
        $cellX = new AtomicValue("x");
        $cellY = new AtomicValue("y");
        $cellZ = new AtomicValue("z");
        $rowXYZ = new VerticalContainer();
        $rowXYZ->add($cellX);
        $rowXYZ->add($cellY);
        $rowXYZ->add($cellZ);
        
        $table = new HorizontalContainer();
        $table->add($rowAB);
        $table->add($rowXYZ);
        
        $this->installSynchronizersTo($table);
        
        $this->assertDimensions([
            [$table, 6, 2],
            
            [$rowAB, 6, 1],
            [$rowXYZ, 6, 1],
            
            [$cellA, 3, 1],
            [$cellB, 3, 1],
            
            [$cellX, 2, 1],
            [$cellY, 2, 1],
            [$cellZ, 2, 1],
        ]);
    }

    public function testVerticalStickyCells()
    {
        /**
         * 
         *  _______________________________________   
         *  |          |            |             |   
         *  |          |            |             |   
         *  |          |            |             |   
         *  |   a      |      b1    |    b2       |   
         *  |          |            |             |   
         *  |__________|____________|_____________|   
         *  |          |                          |   
         *  |          |                          |   
         *  |          |           y              |   
         *  |   x      |                          |   
         *  |          |                          |   
         *  |__________|__________________________|   
         * 
         */
        
        $cellA = new AtomicValue("a");
        
        $cellB1 = new AtomicValue("b1");
        $cellB2 = new AtomicValue("b1");
        
        $cellX = new AtomicValue("x");
        $cellY = new AtomicValue("y");
        
        $cellB = new VerticalContainer();
        $cellB->add($cellB1);
        $cellB->add($cellB2);
        
        $rowAB1B2 = new VerticalContainer();
        $rowAB1B2->add($cellA);
        $rowAB1B2->add($cellB);
        
        $rowXY = new VerticalContainer();
        $rowXY->add($cellX);
        $rowXY->add($cellY);
        
        $table = new HorizontalContainer();
        $table->add($rowAB1B2);
        $table->add($rowXY);
        
        $this->installSynchronizersTo($table);
        
        $this->assertDimensions([
            [$table, 3, 2],
            
            [$rowAB1B2, 3, 1],
            [$rowXY, 3, 1],
            
            [$cellA, 1, 1],
            [$cellB1, 1, 1],
            [$cellB2, 1, 1],
            
            [$cellX, 1, 1],
            [$cellY, 2, 1],
        ]);
        
    }
    
    public function testHorizontalStickyCells()
    {
        /**
         * ____________________________________ 
         * |                 |                |
         * |                 |                | 
         * |                 |                | 
         * |       a         |       x        | 
         * |                 |                | 
         * |                 |                | 
         * |_________________|________________| 
         * |                 |                | 
         * |                 |                | 
         * |                 |                | 
         * |       b1        |                | 
         * |                 |                | 
         * |                 |                | 
         * |_________________|      y         | 
         * |                 |                | 
         * |                 |                | 
         * |      b2         |                | 
         * |                 |                | 
         * |_________________|________________| 
         */
    
        
        $cellA = new AtomicValue("a");
        
        $cellB1 = new AtomicValue("b1");
        $cellB2 = new AtomicValue("b1");
        
        $cellX = new AtomicValue("x");
        $cellY = new AtomicValue("y");
        
        $cellB = new HorizontalContainer();
        $cellB->add($cellB1);
        $cellB->add($cellB2);
        
        $colAB1B2 = new HorizontalContainer();
        $colAB1B2->add($cellA);
        $colAB1B2->add($cellB);
        
        $colXY = new HorizontalContainer();
        $colXY->add($cellX);
        $colXY->add($cellY);
        
        $table = new VerticalContainer();
        $table->add($colAB1B2);
        $table->add($colXY);
        
        $this->installSynchronizersTo($table);
        
        $this->assertDimensions([
            [$table, 2, 3],
            
            [$colAB1B2, 1, 3],
            [$colXY, 1, 3],
            
            [$cellA, 1, 1],
            [$cellB1, 1, 1],
            [$cellB2, 1, 1],
            
            [$cellX, 1, 1],
            [$cellY, 1, 2],
        ]);
    }
    
    public function testAllFeaturesAtOnce()
    {
        /**
         * __________________________________________________________________________
         * |          |          |                            |                     |
         * |          |          |                            |                     |
         * |          |          |                            |                     |
         * |          |          |                            |                     |
         * |  h1_1    | h1_2     |                            |       h3            |
         * |          |          |                            |                     |
         * |          |          |                            |                     |
         * |__________|__________|............................|_____________________|
         * |          .          |                            |                     |
         * |          .          |                            |                     |
         * |          .          |                            |                     |
         * |          .          |                            |                     |
         * |      c1_1.          |           h2               |                     |
         * |          .          |                            |      c3_1           |
         * |          .          |                            |                     |
         * |          .          |                            |                     |
         * |          .          |                            |                     |
         * |          .          |                            |                     |
         * |__________.__________|____________________________|_____________________|
         * |          |     |    |                            |                     |
         * |          |     |    |                            |                     |
         * |          |     |    |                            |       c3_2_1        |
         * |          |     |    |          c2_1              |                     |
         * |          |c1_2_|c1_2|............................|_____________________|
         * |  c1_2_1  |2_1  |_2_2|                            |                     |
         * |          |     |    |                            |                     |
         * |          |     |    |                            |       c3_2_2        |
         * |          |     |    |                            |                     |
         * |__________|_____|____|____________________________|_____________________|
         */
        
            //building col1
        
        //cell c1_2_2_1
        $c1_2_2_1 = new AtomicValue("c1_2_2_1");
        
        //cell c1_2_2_2
        $c1_2_2_2 = new AtomicValue("c1_2_2_2");
        
        //cell c1_2_2 (c1_2_2_1, c1_2_2_2)
        $c1_2_2 = new VerticalContainer();
        $c1_2_2->add($c1_2_2_1);
        $c1_2_2->add($c1_2_2_2);
        
        //cell c1_2_1
        $c1_2_1 = new AtomicValue("c1_2_1");
        
        //cell c1_2 (c1_2_1, c1_2_2)
        $c1_2 = new VerticalContainer();
        $c1_2->add($c1_2_1);
        $c1_2->add($c1_2_2);
        
        //cell c1_1
        $c1_1 = new AtomicValue("c1_1");
        
        //cell h1_1
        $h1_1 = new AtomicValue("h1_1");
        
        //cell h1_2
        $h1_2 = new AtomicValue("h1_2");
        
        //cell h1 (h1_1, h1_2)
        $h1 = new VerticalContainer();
        $h1->add($h1_1);
        $h1->add($h1_2);
        
        //column 1 (h1, c1_1, c2_2)
        $col1 = new HorizontalContainer();
        $col1->add($h1);
        $col1->add($c1_1);
        $col1->add($c1_2);
        
            //building col2
        
        //cell h2
        $h2 = new AtomicValue("h2");
        
        //cell c2_1
        $c2_1 = new AtomicValue("c2_1");

        //column 2 (h2, c2_1)
        $col2 = new HorizontalContainer();
        $col2->add($h2);
        $col2->add($c2_1);
        
            //building col3
        
        //cell c3_2_1
        $c3_2_1 = new AtomicValue("c3_2_1");
        
        //cell c3_2_2
        $c3_2_2 = new AtomicValue("c3_2_2");
        
        //cell c3_2
        $c3_2 = new HorizontalContainer();
        $c3_2->add($c3_2_1);
        $c3_2->add($c3_2_2);
        
        //cell c3_1
        $c3_1 = new AtomicValue("c3_1");
        
        //celll h3
        $h3 = new AtomicValue("h3");
        
        //column 3(h3, c3_1, c3_2)
        $col3 = new HorizontalContainer();
        $col3->add($h3);
        $col3->add($c3_1);
        $col3->add($c3_2);
        
            //building the whole table

        //table (col1, col2, col3)
        $table = new VerticalContainer();
        $table->add($col1);
        $table->add($col2);
        $table->add($col3);
        
        $this->installSynchronizersTo($table);
        
        //assert dimensions
        $this->assertDimensions([
            [$table, 5, 4],
            
            [$col1, 3, 4],
            [$col2, 1, 4],
            [$col3, 1, 4],
            
            [$h1, 3, 1],
            [$h1_1, 1, 1],
            [$h1_2, 2, 1],
            
            [$c1_1, 3, 1],
            
            [$c1_2, 3, 2],
            [$c1_2_1, 1, 2],
            [$c1_2_2, 2, 2],
            
            [$c1_2_2_1, 1, 2],
            [$c1_2_2_2, 1, 2],
            
            [$h2, 1, 2],
            [$c2_1, 1, 2],
            
            [$h3, 1, 1],
            [$c3_1, 1, 1],
            [$c3_2, 1, 2],
            [$c3_2_1, 1, 1],
            [$c3_2_2, 1, 1],
        ]);
        
    }
    
    private function installSynchronizersTo(Element $someTable)
    {
        $widthSynchronizer = new SynchronizerImpl(new WidthSyncStrategy());
        $heightSynchronizer = new SynchronizerImpl(new HeightSyncStrategy());
        
        $gridSyncInstaller = new GridSynchronizerInstaller($widthSynchronizer, $heightSynchronizer);
        $stickyCellsInstaller = new StickyCellsSynchronizerInstaller($widthSynchronizer, $heightSynchronizer);
        
        $someTable->accept($stickyCellsInstaller);
        $someTable->accept($gridSyncInstaller);
    }
    
    /**
     * 
     * @param array $expectedDimensionsOfElements like
     * <pre>
     * [
     *     [Element $cell, int $width, int $height],
     *     ...
     * ]
     * </pre>
     */
    private function assertDimensions(array $expectedDimensionsOfElements)
    {
        /* @var $element Element */
        foreach ($expectedDimensionsOfElements as $elementAndItsExpectedDimensions) {
            list ($element, $expectedWidth, $expectedHeight) = $elementAndItsExpectedDimensions;
            $this->assertEquals($expectedWidth, $element->getWidth());
            $this->assertEquals($expectedHeight, $element->getHeight());
        }
    }
}
