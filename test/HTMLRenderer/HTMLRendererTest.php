<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer;

use DOMDocument;
use lukaszmakuch\ObjectAttributeContainer\Impl\ObjectAttributeContainerImpl;
use lukaszmakuch\TableRenderer\HorizontalContainer;
use lukaszmakuch\TableRenderer\TextValue;
use lukaszmakuch\TableRenderer\VerticalContainer;
use PHPUnit_Framework_TestCase;

class HTMLRendererTest extends PHPUnit_Framework_TestCase
{
    
    public function testRenderingTable()
    {
        $attrs = new ObjectAttributeContainerImpl();
        $builder = new HTMLRendererBuilder();
        $builder->setAttributeContainer($attrs);
        $htmlRenderer = $builder->buildRenderer();
        
        /**
         * _________
         * |a|  x  |
         * | |_____|
         * |_|  y  |
         * | |__ __|
         * |b|  |  |
         * | |z1|z2|
         * |_|_ |__|
         */
        
        $tableTree = $attrs->addObjAttrs((new VerticalContainer())
            ->add((new HorizontalContainer())
                ->add(new TextValue("a"))
                ->add($attrs->addObjAttrs(
                    new TextValue("b"),
                    ['attrs' => ['class' => 'cell']]
                ))
            )
            ->add((new HorizontalContainer())
                ->add(new TextValue("x"))
                ->add(new TextValue("y"))
                ->add((new VerticalContainer())
                    ->add(new TextValue("z1"))
                    ->add(new TextValue("z2"))
                )
            ), ["attrs" => ['border' => 1]]);
        
        $renderedHTML = $htmlRenderer->renderHTMLBasedOn($tableTree);
        
        //load result to parser
        $dom = new DOMDocument();
        $dom->loadHTML($renderedHTML);
        
        //assert just one <table> with proper attrs
        $domTables = $dom->getElementsByTagName("table");
        $this->assertEquals(1, $domTables->length);
        $domTable = $domTables->item(0);
        $this->assertTrue($domTable->hasAttribute("border"));
        $this->assertEquals(1, $domTable->getAttribute("border"));
        
        //get all 6 <tr>
        $trElements = $domTable->getElementsByTagName("tr");
        $this->assertEquals(6, $trElements->length);
        
        //check row with "a" and "x"
        $axRow = $trElements->item(0);
        $axCells = $axRow->getElementsByTagName("td");
        $this->assertEquals(2, $axCells->length);
        $aCell = $axCells->item(0);
        $this->assertEquals("a", $aCell->nodeValue);
        
        $this->assertEquals(3, $aCell->getAttribute("rowspan"));
        $xCell = $axCells->item(1);
        $this->assertEquals("x", $xCell->nodeValue);
        $this->assertEquals(2, $xCell->getAttribute("rowspan"));
        $this->assertEquals(2, $xCell->getAttribute("colspan"));
        
        //check empty row 1
        $emptyRow1 = $trElements->item(1);
        $this->assertEquals(0, $emptyRow1->getElementsByTagName("td")->length);
        
        //check row with "y"
        $yRow = $trElements->item(2);
        $yCells = $yRow->getElementsByTagName("td");
        $this->assertEquals(1, $yCells->length);
        $yCell = $yCells->item(0);
        $this->assertEquals("y", $yCell->nodeValue);
        $this->assertEquals(2, $yCell->getAttribute("rowspan"));
        $this->assertEquals(2, $yCell->getAttribute("colspan"));
        
        //check row with "b"
        $bRow = $trElements->item(3);
        $bCells = $bRow->getElementsByTagName("td");
        $this->assertEquals(1, $bCells->length);
        $bCell = $bCells->item(0);
        $this->assertEquals("b", $bCell->nodeValue);
        $this->assertEquals(3, $bCell->getAttribute("rowspan"));
        $this->assertTrue($bCell->hasAttribute("class"));
        $this->assertEquals("cell", $bCell->getAttribute("class"));
        
        //check row with "z1" and "z2"
        $zRow = $trElements->item(4);
        $zCells = $zRow->getElementsByTagName("td");
        $this->assertEquals(2, $zCells->length);
        $z1Cell = $zCells->item(0);
        $this->assertEquals("z1", $z1Cell->nodeValue);
        $this->assertEquals(2, $z1Cell->getAttribute("rowspan"));
        $z2Cell = $zCells->item(1);
        $this->assertEquals("z2", $z2Cell->nodeValue);
        $this->assertEquals(2, $z2Cell->getAttribute("rowspan"));
        
        //check empty row 2
        $emptyRow2 = $trElements->item(5);
        $this->assertEquals(0, $emptyRow2->getElementsByTagName("td")->length);
    }
}
