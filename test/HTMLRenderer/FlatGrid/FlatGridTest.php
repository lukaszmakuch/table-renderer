<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\FlatGrid;

use lukaszmakuch\TableRenderer\HTMLRenderer\FlatGrid\Exception\ValueNotFound;
use PHPUnit_Framework_TestCase;

class FlatGridTest extends PHPUnit_Framework_TestCase
{
    public function testHoldingValues()
    {
        /**
         * AAAABB
         * AAAACC
         * DEFFCC
         */
        
        $grid = new FlatGrid();
        
        $a = new ValueHolder("a", 4, 2);
        $b = new ValueHolder("b", 2, 1);
        $c = new ValueHolder("c", 2, 2);
        $d = new ValueHolder("c", 1, 1);
        $e = new ValueHolder("c", 1, 1);
        $f = new ValueHolder("c", 2, 1);
        
        $grid->addValueHolder($a, 0, 0);
        $grid->addValueHolder($b, 4, 0);
        $grid->addValueHolder($c, 4, 1);
        $grid->addValueHolder($d, 0, 2);
        $grid->addValueHolder($e, 1, 2);
        $grid->addValueHolder($f, 2, 2);
        
        //6x3 - dimensions of the whole grid 
        $this->assertEquals(6, $grid->getWidth());
        $this->assertEquals(3, $grid->getHeight());
        
        //row 1: A...B.
        $this->assertTrue($grid->getValueHolderAt(0, 0) === $a);
        $this->assertFalse($grid->hasValueHolderAt(1, 0));
        $this->assertFalse($grid->hasValueHolderAt(2, 0));
        $this->assertFalse($grid->hasValueHolderAt(3, 0));
        $this->assertTrue($grid->getValueHolderAt(4, 0) === $b);
        $this->assertFalse($grid->hasValueHolderAt(5, 0));
        
        //row 2: ....C.
        $this->assertFalse($grid->hasValueHolderAt(0, 1));
        $this->assertFalse($grid->hasValueHolderAt(1, 1));
        $this->assertFalse($grid->hasValueHolderAt(2, 1));
        $this->assertFalse($grid->hasValueHolderAt(3, 1));
        $this->assertTrue($grid->getValueHolderAt(4, 1) === $c);
        $this->assertFalse($grid->hasValueHolderAt(5, 1));
        
        //row 3: DEF...
        $this->assertTrue($grid->getValueHolderAt(0, 2) === $d);
        $this->assertTrue($grid->getValueHolderAt(1, 2) === $e);
        $this->assertTrue($grid->getValueHolderAt(2, 2) === $f);
        $this->assertFalse($grid->hasValueHolderAt(3, 2));
        $this->assertFalse($grid->hasValueHolderAt(3, 2));
        $this->assertFalse($grid->hasValueHolderAt(4, 2));
    }
    
    public function testExceptionIfNoValueIsFound()
    {
        $this->setExpectedException(ValueNotFound::class);
        
        $grid = new FlatGrid();
        
        $grid->getValueHolderAt(123, 456);        
    }
}
