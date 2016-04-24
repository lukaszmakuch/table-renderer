<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\ScalarRenderer;

use lukaszmakuch\TableRenderer\HorizontalContainer;
use lukaszmakuch\TableRenderer\NewAtomicType;
use lukaszmakuch\TableRenderer\ScalarRenderer\NewAtomicTypeRenderer;
use lukaszmakuch\TableRenderer\TableElement;
use lukaszmakuch\TableRenderer\TextValue;
use lukaszmakuch\TableRenderer\VerticalContainer;
use PHPUnit_Framework_TestCase;

class ScalarRendererTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ScalarRenderer
     */
    private $renderer;

    protected function setUp()
    {
        $this->renderer = (new ScalarRendererBuilder)
            ->addRenderer(NewAtomicType::class, new NewAtomicTypeRenderer())
            ->build();
    }
    
    public function testRenderingAsScalars()
    {
        $this->assertEqualScalarRepresentation(
            (new VerticalContainer())
                ->add((new HorizontalContainer())
                    ->add(new TextValue("a"))
                    ->add(new NewAtomicType("b"))
                )
                ->add(new TextValue("c")),
            [
                "type" => "vertical-container",
                "value" => [
                    [
                        "type" => "horizontal-container",
                        "value" => [
                            ["type" => "text", "value" => "a"],
                            ["type" => "new-atomic-type", "value" => "b"]
                        ]
                    ],
                    ["type" => "text", "value" => "c"]
                ]
            ]
        );
    }

    private function assertEqualScalarRepresentation(
        TableElement $model,
        $scalarRepresentation
    ) {
        $this->assertEquals(
            $this->renderer->getScalarRepresentationOf($model),
            $scalarRepresentation
        );
    }
}
