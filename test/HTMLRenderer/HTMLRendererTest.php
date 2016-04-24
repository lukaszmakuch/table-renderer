<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer;

use lukaszmakuch\ObjectAttributeContainer\Impl\ObjectAttributeContainerImpl;
use lukaszmakuch\TableRenderer\HorizontalContainer;
use lukaszmakuch\TableRenderer\HTMLRenderer\Exception\UnableToRender;
use lukaszmakuch\TableRenderer\TextValue;
use lukaszmakuch\TableRenderer\VerticalContainer;
use lukaszmakuch\TableRenderer\NewAtomicType;
use lukaszmakuch\TableRenderer\HTMLRenderer\NewAtomicTypeRenderer;
use lukaszmakuch\TableRenderer\UnsupportedAtomicValue;
use PHPUnit_Framework_TestCase;

class HTMLRendererTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var HTMLRendererBuilder
     */
    private $builder;
    
    protected function setUp()
    {
        $this->builder = new HTMLRendererBuilder();
    }
    
    public function testRenderingComplexTable()
    {
        /**
         * __________________________________________________________________________
         * |          |          |                            |                     |
         * |          |          |                            |                     |
         * |          |          |                            |                     |
         * |          |          |                            |                     |
         * |  h1_1    | h1_2     |                            |       h3            |
         * |          |          |                            |                     |
         * |          |          |                            |                     |
         * |__________|__________|............................|_____________________|
         * |          .          |                            |                     |
         * |          .          |                            |                     |
         * |          .          |                            |                     |
         * |          .          |                            |                     |
         * |      c1_1.          |           h2               |                     |
         * |          .          |                            |      c3_1           |
         * |          .          |                            |                     |
         * |          .          |                            |                     |
         * |          .          |                            |                     |
         * |          .          |                            |                     |
         * |__________.__________|____________________________|_____________________|
         * |          |     |    |                            |                     |
         * |          |     |    |                            |                     |
         * |          |     |    |                            |       c3_2_1        |
         * |          |     |    |          c2_1              |                     |
         * |          |c1_2_|c1_2|............................|_____________________|
         * |  c1_2_1  |2_1  |_2_2|                            |                     |
         * |          |     |    |                            |                     |
         * |          |     |    |                            |       c3_2_2        |
         * |          |     |    |                            |                     |
         * |__________|_____|____|____________________________|_____________________|
         */

        ob_start();
        ?>
            <table>
                <tr>
                    <td colspan="1" rowspan="1">h1_1</td>
                    <td colspan="2" rowspan="1">h1_2</td>
                    <td colspan="1" rowspan="2">h2</td>
                    <td colspan="1" rowspan="1">h3</td>
                </tr>
                <tr>
                    <td colspan="3" rowspan="1">c1_1</td>
                    <td colspan="1" rowspan="1">c3_1</td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="2">c1_2_1</td>
                    <td colspan="1" rowspan="2">c1_2_2_1</td>
                    <td colspan="1" rowspan="2">c1_2_2_2</td>
                    <td colspan="1" rowspan="2">c2_1</td>
                    <td colspan="1" rowspan="1">c3_2_1</td>

                </tr>
                <tr>
                    <td colspan="1" rowspan="1">c3_2_2</td>
                </tr>

            </table>
        <?php
        $expectedHTML = ob_get_clean();
        
        $tree = 
            (new VerticalContainer())
                ->add((new HorizontalContainer())
                    ->add((new VerticalContainer())
                        ->add(new TextValue("h1_1"))
                        ->add(new TextValue("h1_2"))
                    )
                    ->add(new TextValue("c1_1"))
                    ->add((new VerticalContainer())
                        ->add(new TextValue("c1_2_1"))
                        ->add((new VerticalContainer())
                            ->add(new TextValue("c1_2_2_1"))
                            ->add(new TextValue("c1_2_2_2"))
                        )
                    )
                )
                ->add((new HorizontalContainer())
                    ->add(new TextValue("h2"))
                    ->add(new TextValue("c2_1"))
                )
                ->add((new HorizontalContainer())
                    ->add(new TextValue("h3"))
                    ->add(new TextValue("c3_1"))
                    ->add((new HorizontalContainer())
                        ->add(new TextValue("c3_2_1"))
                        ->add(new TextValue("c3_2_2"))
                    )
                )
        ;
        
        $renderer = $this->builder->buildRenderer();
        
        $renderedHTML = $renderer->renderHTMLBasedOn($tree);
        
        $this->assertXmlStringEqualsXmlString($expectedHTML, $renderedHTML);
    }
    
    public function testAddingHTMLAttributes()
    {
        /**
         * _______________________
         * |  a       | b        |
         * |__________|__________|
         */

        ob_start();
        ?>
            <table border="1">
                <tr>
                    <td colspan="1" rowspan="1" style="color: #f00">a</td>
                    <td colspan="1" rowspan="1">b</td>
                </tr>
            </table>
        <?php
        $expectedHTML = ob_get_clean();
        
        $attrs = new ObjectAttributeContainerImpl();
        $this->builder->setAttributeContainer($attrs);
        
        $tree = 
            $attrs->addObjAttrs(
                (new VerticalContainer())
                    ->add($attrs->addObjAttrs(
                        new TextValue("a"),
                        ["attrs" => ["style" => "color: #f00"]]
                    ))
                    ->add(new TextValue("b"))
                ,
                ["attrs" => ["border" => 1]]
            )
        ;
        
        $renderer = $this->builder->buildRenderer();
        
        $renderedHTML = $renderer->renderHTMLBasedOn($tree);
        
        $this->assertXmlStringEqualsXmlString($expectedHTML, $renderedHTML);
    }
    
    public function testExceptionWhenUnsupportedAtomicValue()
    {
        $renderer = $this->builder->buildRenderer();
        
        $this->setExpectedException(UnableToRender::class);
        
        $renderer->renderHTMLBasedOn(new UnsupportedAtomicValue());
    }
    
    public function testBuildingWithNewAtomicRenderer()
    {
        /**
         * ___________
         * |test-value|
         * |__________|
         */

        $oneCellTable = new NewAtomicType("test-value");
        
        ob_start();
        ?>
            <table>
                <tr>
                    <td colspan="1" rowspan="1">test-value</td>
                </tr>
            </table>
        <?php
        
        $expectedHTML = ob_get_clean();
        
        //add support of a new atomic value
        $this->builder->addAtomicValueRenderer(
            NewAtomicType::class,
            new NewAtomicTypeRenderer()
        );
        $renderer = $this->builder->buildRenderer();
        
        $renderedHTML = $renderer->renderHTMLBasedOn($oneCellTable);
        
        $this->assertXmlStringEqualsXmlString($expectedHTML, $renderedHTML);
    }

}
