<?php

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer;

use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Factory\SynchronizerFactory;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Factory\SynchronizerFactoryImpl;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\HeightSyncStrategy;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\AtomicValue;
use PHPUnit_Framework_TestCase;

class HeightSynchronizerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SynchronizerFactory
     */
    private $synchronizerF;
    
    protected function setUp()
    {
        $this->synchronizerF = new SynchronizerFactoryImpl(new HeightSyncStrategy());
    }
    
    public function testSynchronizingHeight()
    {
        $e1 = new AtomicValue("a");
        $e2 = new AtomicValue("b");
        $synchronizer = $this->synchronizerF->getSynchronizerOf($e1, $e2);
        
        $e1->setWidth(2);
        $e1->setHeight(3);
        
        $e2->setWidth(3);
        $e2->setHeight(2);
        
        $this->assertEquals(3, $e1->getHeight());
        $this->assertEquals(2, $e2->getHeight());
        $this->assertEquals(2, $e1->getWidth());
        $this->assertEquals(3, $e2->getWidth());
        
        $synchronizer->start();
        
        $this->assertEquals(6, $e1->getHeight());
        $this->assertEquals(6, $e2->getHeight());
        $this->assertEquals(2, $e1->getWidth());
        $this->assertEquals(3, $e2->getWidth());
        
        $e2->setHeight(12);
        
        $this->assertEquals(12, $e1->getHeight());
        $this->assertEquals(12, $e2->getHeight());
        $this->assertEquals(2, $e1->getWidth());
        $this->assertEquals(3, $e2->getWidth());
    }
}
