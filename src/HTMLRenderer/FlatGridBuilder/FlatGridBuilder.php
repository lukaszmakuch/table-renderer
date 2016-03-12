<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\FlatGridBuilder;

use lukaszmakuch\TableRenderer\HTMLRenderer\FlatGrid\FlatGrid;
use lukaszmakuch\TableRenderer\HTMLRenderer\FlatGrid\ValueHolder;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\AtomicValue;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\HorizontalContainer;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\TreeVisitor;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\VerticalContainer;

/**
 * Builds FlatGrid
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class FlatGridBuilder implements TreeVisitor
{
    private $firstX = 0;
    private $firstY = 0;
    private $grid;
    
    public function __construct()
    {
        $this->grid = new FlatGrid();
    }
    
    public function setFirstX($firstX)
    {
        $this->firstX = $firstX;
    }
    
    public function setFirstY($firstY)
    {
        $this->firstY = $firstY;
    }
    
    /**
     * @return FlatGrid
     */
    public function getBuiltGrid()
    {
        return $this->grid;
    }
    
    public function visitAtomicValue(AtomicValue $value)
    {
        $valueHolder = new ValueHolder(
            $value->getHeldValue(), 
            $value->getWidth(), 
            $value->getHeight()
        );
        $this->grid->addValueHolder($valueHolder, $this->firstX, $this->firstY);
    }

    public function visitHorizontalContainer(HorizontalContainer $container)
    {
        $moveY = 0;
        foreach ($container->getElements() as $horizontalElement) {
            $gridBuilder = clone $this;
            $gridBuilder->setFirstY($this->firstY + $moveY);
            $horizontalElement->accept($gridBuilder);
            $moveY += $horizontalElement->getHeight();
        }
    }

    public function visitVerticalContainer(VerticalContainer $container)
    {
        $moveX = 0;
        foreach ($container->getElements() as $verticalElement) {
            $gridBuilder = clone $this;
            $gridBuilder->setFirstX($this->firstX + $moveX);
            $verticalElement->accept($gridBuilder);
            $moveX += $verticalElement->getWidth();
        }
    }

}