<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Installer;

use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\AtomicValue;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\HorizontalContainer;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Synchronizer;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\TreeVisitor;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\VerticalContainer;

/**
 * Keeps a correct grid. 
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class GridSynchronizerInstaller implements TreeVisitor
{
    /**
     * @var Synchronizer 
     */
    private $widthSynchronizer;
    
    /**
     * @var Synchronizer 
     */
    private $heightSynchronizer;
    
    public function __construct(Synchronizer $widthSynchronizer, Synchronizer $heightSynchronizer)
    {
        $this->widthSynchronizer = $widthSynchronizer;
        $this->heightSynchronizer = $heightSynchronizer;
    }
    
    public function visitAtomicValue(AtomicValue $value)
    {
        return;
    }

    public function visitHorizontalContainer(HorizontalContainer $container)
    {
        foreach ($container->getElements() as $itsElement) {
            $this->widthSynchronizer->synchronize($container, $itsElement);
            $itsElement->accept($this);
        }
    }

    public function visitVerticalContainer(VerticalContainer $container)
    {
        foreach ($container->getElements() as $itsElement) {
            $this->heightSynchronizer->synchronize($container, $itsElement);
            $itsElement->accept($this);
        }
    }
}
