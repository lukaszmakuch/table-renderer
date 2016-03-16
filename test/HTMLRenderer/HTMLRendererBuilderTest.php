<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer;

use DOMDocument;
use DOMText;
use lukaszmakuch\TableRenderer\AtomicCellValue;
use lukaszmakuch\TableRenderer\HTMLRenderer\AtomicValueRenderer\AtomicValueRenderer;
use PHPUnit_Framework_TestCase;

class NewAtomicType extends AtomicCellValue
{
    public $itsValue;
    
    public function __construct($itsValue)
    {
        $this->itsValue = $itsValue;
    }
}

class NewAtomicTypeRenderer implements AtomicValueRenderer
{
    public function render(AtomicCellValue $value)
    {
        /* @var $value NewAtomicType */
        return new DOMText($value->itsValue);
    }
}

class HTMLRendererBuilderTest extends PHPUnit_Framework_TestCase
{
    public function testBuildingWithNewAtomicRenderer()
    {
        $builder = new HTMLRendererBuilder();
        $builder->addAtomicValueRenderer(
            NewAtomicType::class,
            new NewAtomicTypeRenderer()
        );
        $renderer = $builder->buildRenderer();
        
        $oneCellTable = new NewAtomicType("test-value");
        
        $renderedTable = $renderer->renderHTMLBasedOn($oneCellTable);
        
        $DOM = new DOMDocument();
        $DOM->loadHTML($renderedTable);
        $this->assertEquals(
            "test-value",
            $DOM->getElementsByTagName("td")->item(0)->nodeValue
        );
    }
}
