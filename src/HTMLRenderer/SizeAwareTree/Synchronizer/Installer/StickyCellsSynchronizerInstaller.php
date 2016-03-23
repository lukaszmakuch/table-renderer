<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Installer;

use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\AtomicValue;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Container;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Element;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\HorizontalContainer;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Synchronizer;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\TreeVisitor;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\VerticalContainer;

/**
 * Adds sticky cells.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class StickyCellsSynchronizerInstaller implements TreeVisitor
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
        $this->synchronizeElementsOfContainer(
            $container, 
            VerticalContainer::class, 
            $this->widthSynchronizer
        );

        $this->visitEveryElementOf($container);
    }

    public function visitVerticalContainer(VerticalContainer $container)
    {
        $this->synchronizeElementsOfContainer(
            $container, 
            HorizontalContainer::class, 
            $this->heightSynchronizer
        );
        
        $this->visitEveryElementOf($container);
    }
    
    private function synchronizeElementsOfContainer(
        Container $container,
        $classOfItsSynchronizedContainers,
        Synchronizer $elemSyncStrategy
    ) {
        //get all of horizontal containers held by this container
        $itsSynchronizedContainers = array_filter(
            $container->getElements(), 
            function (Element $e) use ($classOfItsSynchronizedContainers) {
                return $e instanceof $classOfItsSynchronizedContainers;
            }
        );
        
        //group them by the number of their elements
        $containersByNumberOfElems = $this->getContainersByNumberOfTheirElements($itsSynchronizedContainers);
            
        //synchronize height of their elements
        foreach ($containersByNumberOfElems as $containersWithElemsToSync) {
            $this->synchronizeElementsOf($containersWithElemsToSync, $elemSyncStrategy);
        }
    }
    
    /**
     * @param Container[] $containers
     * 
     * @return [[Container, Container], [Container, Container, Container]]
     */
    private function getContainersByNumberOfTheirElements($containers)
    {
        return array_reduce(
            $containers, 
            function ($containerByNumOfElems, Container $containerToPut) {
                $numberOfItsElems = count($containerToPut->getElements());
                if (!isset($containerByNumOfElems[$numberOfItsElems])) {
                    $containerByNumOfElems[$numberOfItsElems] = [];
                }
                
                $containerByNumOfElems[$numberOfItsElems][] = $containerToPut;
                return $containerByNumOfElems;
            }, 
            []
        );
    }
    
    /**
     * 
     * @param Container[] $containers
     * @param Synchronizer $syncStrategy
     */
    private function synchronizeElementsOf(array $containers, Synchronizer $syncStrategy)
    {
        if (empty($containers)) {
            return;
        }
        
        $elementsOfEachContainer = [];
        foreach ($containers as $singleContainer) {
            $elementsOfEachContainer[] = $singleContainer->getElements();
        }
        
        $numberOfElementsOfEachContainer = count($elementsOfEachContainer[0]);
        
        foreach (range(0, $numberOfElementsOfEachContainer - 1) as $elementPosition) {
            $this->synchronizeElements(
                array_column($elementsOfEachContainer, $elementPosition), 
                $syncStrategy
            );
        }
    }
    
    /**
     * @param Element[] $elements
     * @param Synchronizer $syncStrategy
     */
    private function synchronizeElements(array $elements, Synchronizer $syncStrategy)
    {
        $previousElement = null;
        foreach ($elements as $currentElement) {
            if ($previousElement !== null) {
                $syncStrategy->synchronize($currentElement, $previousElement);
            }
            
            $previousElement = $currentElement;
        }
    }
    
    private function visitEveryElementOf(Container $container)
    {
        foreach ($container->getElements() as $elemToVisit) {
            $elemToVisit->accept($this);
        }
    }
}
