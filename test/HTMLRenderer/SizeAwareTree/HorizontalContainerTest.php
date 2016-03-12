<?php

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree;

use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Factory\SynchronizerFactoryImpl;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\WidthSyncStrategy;
use PHPUnit_Framework_TestCase;

class HorizontalContainerTest extends PHPUnit_Framework_TestCase
{
    public function testSettingDimensions()
    {
        /*
         * We have a horizontal container like that
         *  _____
         * |  a  |
         * |_____|
         * |  b  |
         * |_____|
         */
        
        $widthSynchronizerF = new SynchronizerFactoryImpl(new WidthSyncStrategy());
        $container = new HorizontalContainer($widthSynchronizerF);
        $a = new AtomicValue("a");
        $container->add($a);
        $b = new AtomicValue("b");
        $container->add($b);
        
        //by default it should be 1 x 2
        $this->assertEquals(1, $container->getWidth());
        $this->assertEquals(2, $container->getHeight());
        
        //when it becomes wider, it resizes all held elements
        $container->setWidth(3);
        $this->assertEquals(3, $container->getWidth());
        $this->assertEquals(2, $container->getHeight());
        $this->assertEquals(3, $a->getWidth());
        $this->assertEquals(1, $a->getHeight());
        $this->assertEquals(3, $b->getWidth());
        $this->assertEquals(1, $b->getHeight());
        
        //when it becomes taller, it resizes all held elements
        $container->setHeight(6);
        $this->assertEquals(3, $container->getWidth());
        $this->assertEquals(6, $container->getHeight());
        $this->assertEquals(3, $a->getWidth());
        $this->assertEquals(3, $a->getHeight());
        $this->assertEquals(3, $b->getWidth());
        $this->assertEquals(3, $b->getHeight());
        
        //when one element becomes wider, it resizes the whole composite
        $b->setWidth(6);
        $this->assertEquals(6, $container->getWidth());
        $this->assertEquals(6, $container->getHeight());
        $this->assertEquals(6, $a->getWidth());
        $this->assertEquals(3, $a->getHeight());
        $this->assertEquals(6, $b->getWidth());
        $this->assertEquals(3, $b->getHeight());
        
        //when a wider element is added, it resizes the whole composite
        $c = new AtomicValue("c");
        $c->setWidth(12);
        $container->add($c);
        $this->assertEquals(12, $container->getWidth());
        $this->assertEquals(7, $container->getHeight());
        $this->assertEquals(12, $a->getWidth());
        $this->assertEquals(3, $a->getHeight());
        $this->assertEquals(12, $b->getWidth());
        $this->assertEquals(3, $b->getHeight());
        $this->assertEquals(12, $c->getWidth());
        $this->assertEquals(1, $c->getHeight());
    }
}
