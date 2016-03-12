<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer;

/**
 * Checks building and visiting structures.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class StructureTest extends \PHPUnit_Framework_TestCase
{
    public function testHoldingValuesByVerticalContainer()
    {
        $this->assertContainerCanHoldValues(
            new VerticalContainer(), 
            [new TextValue("a"), new TextValue("b")]
        );
    }
    
    public function testHoldingValuesByHorizontalContainer()
    {
        $this->assertContainerCanHoldValues(
            new HorizontalContainer(), 
            [new TextValue("a"), new TextValue("b")]
        );
    }
    
    public function testVisitingAtomicCellValues()
    {
        $this->assertCallingVisitorMethod(
            new TextValue("a"), 
            "visitAtomicCellValue"
        );
    }

    public function testVisitingVerticalContainer()
    {
        $this->assertCallingVisitorMethod(
            new VerticalContainer(), 
            "visitVerticalContainer"
        );
    }
    
    public function testVisitingHorizontalContainer()
    {
        $this->assertCallingVisitorMethod(
            new HorizontalContainer(), 
            "visitHorizontalContainer"
        );
    }
    
    private function assertContainerCanHoldValues(Container $container, $values)
    {
        foreach ($values as $singleValueToHold) {
            $container = $container->add($singleValueToHold);
        }
        
        $this->assertEquals($values, $container->getElements());
    }
    
    private function assertCallingVisitorMethod(
        TableElement $visitedElement,
        $expectedMethodName
    ) {
        $visitor = $this->getMock(TableVisitor::class);
        
        $visitor->expects($this->once())
            ->method($expectedMethodName)
            ->with($visitedElement);
        
        $visitedElement->accept($visitor);
    }
}
