<?php

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree;

use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Factory\SynchronizerFactoryImpl;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\HeightSyncStrategy;
use PHPUnit_Framework_TestCase;

class VerticalContainerTest extends PHPUnit_Framework_TestCase
{
    public function testSettingDimensions()
    {
        /*
         * We have a vertical composite like that
         *  _______
         * | a | b |
         * |___|___| 
         */
        
        $heightSynchronizerF = new SynchronizerFactoryImpl(new HeightSyncStrategy());
        $container = new VerticalContainer($heightSynchronizerF);
        $a = new AtomicValue("a");
        $container->add($a);
        $b = new AtomicValue("b");
        $container->add($b);
        
        //by default it should be 2 x 1
        $this->assertEquals(2, $container->getWidth());
        $this->assertEquals(1, $container->getHeight());
        
        //when it becomes taller, it resizes all held elements
        $container->setHeight(3);
        $this->assertEquals(2, $container->getWidth());
        $this->assertEquals(3, $container->getHeight());
        $this->assertEquals(1, $a->getWidth());
        $this->assertEquals(3, $a->getHeight());
        $this->assertEquals(1, $b->getWidth());
        $this->assertEquals(3, $b->getHeight());
        
        //when it becomes wider, it resizes all held elements
        $container->setWidth(6);
        $this->assertEquals(6, $container->getWidth());
        $this->assertEquals(3, $container->getHeight());
        $this->assertEquals(3, $a->getWidth());
        $this->assertEquals(3, $a->getHeight());
        $this->assertEquals(3, $b->getWidth());
        $this->assertEquals(3, $b->getHeight());
        
        //when one element becomes taller, it resizes the whole composite
        $b->setHeight(6);
        $this->assertEquals(6, $container->getWidth());
        $this->assertEquals(6, $container->getHeight());
        $this->assertEquals(3, $a->getWidth());
        $this->assertEquals(6, $a->getHeight());
        $this->assertEquals(3, $b->getWidth());
        $this->assertEquals(6, $b->getHeight());
        
        //when a taller element is added, it resizes the whole composite
        $c = new AtomicValue("c");
        $c->setHeight(12);
        $container->add($c);
        $this->assertEquals(12, $container->getHeight());
        $this->assertEquals(7, $container->getWidth());
        $this->assertEquals(12, $a->getHeight());
        $this->assertEquals(3, $a->getWidth());
        $this->assertEquals(12, $b->getHeight());
        $this->assertEquals(3, $b->getWidth());
        $this->assertEquals(12, $c->getHeight());
        $this->assertEquals(1, $c->getWidth());
    }
}
