<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer;

use DOMDocument;
use DOMElement;
use DOMNode;
use lukaszmakuch\ObjectAttributeContainer\ObjectAttributeContainer;
use lukaszmakuch\TableRenderer\HTMLRenderer\AtomicValueRenderer\AtomicValueRenderer;
use lukaszmakuch\TableRenderer\HTMLRenderer\FlatGrid\FlatGrid;
use lukaszmakuch\TableRenderer\HTMLRenderer\FlatGrid\ValueHolder;
use lukaszmakuch\TableRenderer\HTMLRenderer\FlatGridBuilder\FlatGridBuilder;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTreeBuilder\SizeAwareTreeBuilder;
use lukaszmakuch\TableRenderer\TableElement;

/**
 * Renders HTML tables based on tree structures.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class HTMLRenderer
{
    private static $HTML_ATTRS_KEY = "attrs";
    
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
        $renderedDocument = new DOMDocument();
        $newTable = $renderedDocument->createElement("table");
        $this->addHTMLAttrs($newTable, $table);
        $flatGrid = $this->buildGridBasedOn($table);
        for($rowIndex = 0; $rowIndex < $flatGrid->getHeight(); $rowIndex++) {
            $newRow = $renderedDocument->createElement("tr");
            $this->fillRowWithCells($renderedDocument, $newRow, $rowIndex, $flatGrid);
            $newTable->appendChild($newRow);
        }
        
        $renderedDocument->appendChild($newTable);
        return $renderedDocument->saveHTML();
    }
    
    /**
     * Fills a whole HTML row with cells read from the given flat grid.
     * 
     * @param DOMDocument $targetDocument
     * @param DOMElement $targetRow
     * @param int $rowIndex
     * @param FlatGrid $sourceGrid
     * 
     * @return
     */
    private function fillRowWithCells(
        \DOMDocument $targetDocument, 
        \DOMElement $targetRow, 
        $rowIndex, 
        FlatGrid $sourceGrid
    ) {
        for($colIndex = 0; $colIndex < $sourceGrid->getWidth(); $colIndex++) {
            if ($sourceGrid->hasValueHolderAt($colIndex, $rowIndex)) {
                $cellModel = $sourceGrid->getValueHolderAt($colIndex, $rowIndex);
                $this->addCell($cellModel, $targetRow, $targetDocument);
            }
        }
    }
    
    /**
     * @param ValueHolder $cellModel
     * @param DOMElement $targetRow
     * @param DOMDocument $targetDocument
     * 
     * @return null
     */
    private function addCell(
        ValueHolder $cellModel, 
        DOMElement $targetRow, 
        \DOMDocument $targetDocument
    ) {
        $td = $targetDocument->createElement("td");
        $this->addHTMLAttrs($td, $cellModel->getHeldValue());
        $td->appendChild($this->renderCellValue($cellModel));
        $td->setAttribute("colspan", $cellModel->getWidth());
        $td->setAttribute("rowspan", $cellModel->getHeight());
        $targetRow->appendChild($td);
    }

    /**
     * @param ValueHolder $cellModel
     * 
     * @return DOMNode
     */
    private function renderCellValue(ValueHolder $cellModel)
    {
        return $this->atomicValueRenderer->render($cellModel->getHeldValue());
    }
    
    /**
     * @param TableElement $treeTableModel
     * 
     * @return FlatGrid
     */
    private function buildGridBasedOn(TableElement $treeTableModel)
    {
        //get builders based on prototypes
        $sizeAwareTreeBuilder = clone $this->sizeAwareTreeBuilderPrototype;
        $flatGridBuilder = clone $this->flatGridBuilderPrototype;
        
        //build size aware tree
        $treeTableModel->accept($sizeAwareTreeBuilder);
        $sizeAwareTree = $sizeAwareTreeBuilder->getBuiltSizeAwareTree();

        //build and return flat grid
        $sizeAwareTree->accept($flatGridBuilder);
        return $flatGridBuilder->getBuiltGrid();
    }
    
    /**
     * Reads HTML attributes of the given TableElement model 
     * from the attribute container and then applies them
     * to the given HTML DOMElement.
     * 
     * @param DOMElement $HTMLRepresentation
     * @param TableElement $model
     * 
     * @return null
     */
    private function addHTMLAttrs(
        DOMElement $HTMLRepresentation, 
        TableElement $model
    ) {
        if ($this->attrs->objHasAttr($model, self::$HTML_ATTRS_KEY)) {
            $attrs = $this->attrs->getObjAttrVal($model, self::$HTML_ATTRS_KEY);
            foreach ($attrs as $name => $val) {
                $HTMLRepresentation->setAttribute($name, $val);
            }
        }
    }
}
