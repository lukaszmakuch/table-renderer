<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer;

use DOMDocument;
use lukaszmakuch\ObjectAttributeContainer\ObjectAttributeContainer;
use lukaszmakuch\TableRenderer\HTMLRenderer\AtomicValueRenderer\AtomicValueRenderer;
use lukaszmakuch\TableRenderer\HTMLRenderer\FlatGrid\FlatGrid;
use lukaszmakuch\TableRenderer\HTMLRenderer\FlatGridBuilder\FlatGridBuilder;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTreeBuilder\SizeAwareTreeBuilder;
use lukaszmakuch\TableRenderer\TableElement;

class HTMLRenderer
{
    const HTML_ATTRS_KEY = "attrs";
    
    private $sizeAwareTreeBuilderPrototype;
    private $flatGridBuilderPrototype;
    private $atomicValueRenderer;
    private $attrs;
    
    public function __construct(
        SizeAwareTreeBuilder $sizeAwareTreeBuilderPrototype,
        FlatGridBuilder $flatGridBuilderPrototype,
        AtomicValueRenderer $atomicValueRenderer,
        ObjectAttributeContainer $attributeContainer
    ) {
        $this->sizeAwareTreeBuilderPrototype = $sizeAwareTreeBuilderPrototype;
        $this->flatGridBuilderPrototype = $flatGridBuilderPrototype;
        $this->atomicValueRenderer = $atomicValueRenderer;
        $this->attrs = $attributeContainer;
    }
    
    /**
     * @param TableElement $table
     * 
     * @return String html
     */
    public function renderHTMLBasedOn(TableElement $table)
    {
        $sizeAwareTreeBuilder = clone $this->sizeAwareTreeBuilderPrototype;
        $flatGridBuilder = clone $this->flatGridBuilderPrototype;
        
        $table->accept($sizeAwareTreeBuilder);
        $sizeAwareTree = $sizeAwareTreeBuilder->getBuiltSizeAwareTree();
        
        $sizeAwareTree->accept($flatGridBuilder);
        /* @var $flatGrid FlatGrid */
        $flatGrid = $flatGridBuilder->getBuiltGrid();
        
        $html = new DOMDocument();
        
        $domTable = $html->createElement("table");
        $this->addHTMLAttrs($domTable, $table);
        
        for($rowIndex = 0; $rowIndex < $flatGrid->getHeight(); $rowIndex++) {
            $tr = $html->createElement("tr");
            for($colIndex = 0; $colIndex < $flatGrid->getWidth(); $colIndex++) {
                if ($flatGrid->hasValueHolderAt($colIndex, $rowIndex)) {
                    $valueHolder = $flatGrid->getValueHolderAt($colIndex, $rowIndex);
                    $td = $html->createElement("td");
                    $this->addHTMLAttrs($td, $valueHolder->getHeldValue());
                    $td->appendChild($this->atomicValueRenderer->render($valueHolder->getHeldValue()));
                    $td->setAttribute("colspan", $valueHolder->getWidth());
                    $td->setAttribute("rowspan", $valueHolder->getHeight());
                    $tr->appendChild($td);
                }
            }
            
            $domTable->appendChild($tr);
        }
        
        $html->appendChild($domTable);
        return $html->saveHTML();
    }

    /**
     * Reads HTML attributes of the given TableElement model 
     * from the attribute container and then applies them
     * to the given HTML DOMElement.
     * 
     * @param \DOMElement $HTMLRepresentation
     * @param TableElement $model
     * 
     * @return null
     */
    private function addHTMLAttrs(\DOMElement $HTMLRepresentation, TableElement $model)
    {
        if (!$this->attrs->objHasAttr($model, self::HTML_ATTRS_KEY)) {
            return;
        }
        
        $elementAttrs = $this->attrs->getObjAttrVal($model, self::HTML_ATTRS_KEY);
        foreach ($elementAttrs as $name => $val) {
            $HTMLRepresentation->setAttribute($name, $val);
        }
    }
}
