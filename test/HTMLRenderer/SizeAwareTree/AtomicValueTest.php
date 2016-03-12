<?php

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree;

class AtomicValueTest extends \PHPUnit_Framework_TestCase
{
    public function testHoldingValue()
    {
        $value = new \stdClass();
        $valueHolder = new AtomicValue($value);
        $this->assertTrue($valueHolder->getHeldValue() === $value);
    }
    
    public function testSettingAndReadingDimensions()
    {
        $valueHolder = new AtomicValue("abc");
        
        //default is 1x1
        $this->assertEquals(1, $valueHolder->getWidth());
        $this->assertEquals(1, $valueHolder->getHeight());
        
        //it is possible to override these values
        $valueHolder->setWidth(4);
        $valueHolder->setHeight(2);
        $this->assertEquals(4, $valueHolder->getWidth());
        $this->assertEquals(2, $valueHolder->getHeight());
    }
    
    public function testBeingObserved()
    {
        $observer = $this->getMock(ElementObserver::class);
        $valueHolder = new AtomicValue("abc");
        $valueHolder->observeBy($observer);
        $observer->expects($this->atLeastOnce())
            ->method("noticeChangeOf")
            ->with($valueHolder);
        $valueHolder->setWidth(21);
        $observer->expects($this->atLeastOnce())
            ->method("noticeChangeOf")
            ->with($valueHolder);
        $valueHolder->setHeight(12);
    }
}
