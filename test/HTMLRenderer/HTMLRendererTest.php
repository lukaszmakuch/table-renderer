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
use lukaszmakuch\ObjectAttributeContainer\ObjectAttributeContainer;
use lukaszmakuch\TableRenderer\HorizontalContainer;
use lukaszmakuch\TableRenderer\HTMLRenderer\AtomicValueRenderer\TextRenderer;
use lukaszmakuch\TableRenderer\HTMLRenderer\FlatGridBuilder\FlatGridBuilder;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\HorizontalContainerFactory;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Factory\SynchronizerFactoryImpl;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\HeightSyncStrategy;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\Synchronizer\Strategy\WidthSyncStrategy;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree\VerticalContainerFactory;
use lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTreeBuilder\SizeAwareTreeBuilder;
use lukaszmakuch\TableRenderer\TextValue;
use lukaszmakuch\TableRenderer\VerticalContainer;
use PHPUnit_Framework_TestCase;

class HTMLRendererTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var HTMLRenderer
     */
    private $htmlRenderer;
    
    /**
     * @var ObjectAttributeContainer
     */
    private $attrs;

    protected function setUp()
    {
        $this->attrs = new ObjectAttributeContainerImpl();
        $this->htmlRenderer = new HTMLRenderer(
            new SizeAwareTreeBuilder(
                new VerticalContainerFactory(
                    new SynchronizerFactoryImpl(
                        new HeightSyncStrategy()
                    )
                ),
                new HorizontalContainerFactory(
                    new SynchronizerFactoryImpl(
                        new WidthSyncStrategy()
                    )
                )
            ),
            new FlatGridBuilder(),
            new TextRenderer(),
            $this->attrs  
        );
    }
    
    public function testRenderingTable()
    {
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
        
        $tableTree = (new VerticalContainer())
            ->add((new HorizontalContainer())
                ->add(new TextValue("a"))
                ->add($this->attrs->addObjAttrs(
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
            );
        
        $renderedHTML = $this->htmlRenderer->renderHTMLBasedOn($tableTree);
        
        //load result to parser
        $dom = new DOMDocument();
        $dom->loadHTML($renderedHTML);
        
        //asser just one <table>
        $domTables = $dom->getElementsByTagName("table");
        $this->assertEquals(1, $domTables->length);
        $domTable = $domTables->item(0);
        
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
