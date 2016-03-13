<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

require_once __DIR__ . "/../vendor/autoload.php";

use lukaszmakuch\ObjectAttributeContainer\Impl\ObjectAttributeContainerImpl;
use lukaszmakuch\TableRenderer\HorizontalContainer;
use lukaszmakuch\TableRenderer\HTMLRenderer\HTMLRendererBuilder;
use lukaszmakuch\TableRenderer\VerticalContainer;
use lukaszmakuch\TableRenderer\TextValue;

//will hold attributes used by the HTML renderer
$attrs = new ObjectAttributeContainerImpl();

//table with a border attribute assigned for the HTML renderer
$tableModel = $attrs->addObjAttrs(
    (new HorizontalContainer())

        //cells at height of the red "may" in vertical container
        ->add((new VerticalContainer())

            //"tree", "structures" columns in a horizontal container on the left
            ->add((new HorizontalContainer())
                ->add(new TextValue("tree"))
                ->add(new TextValue("structures"))
            )

            //styled "may" column in the center
            ->add($attrs->addObjAttrs(
                new TextValue("may"), 
                ["attrs" => ["style" => "background-color: #d33; color: #fff"]]
            ))

            //"be", "rendered", "as" cells in a horizontal container on the right
            ->add((new HorizontalContainer())
                ->add(new TextValue("be"))
                ->add(new TextValue("rendered"))
                ->add(new TextValue("as"))
            )
        )

        //"a", "table" bottom cells in a vertical container
        ->add((new VerticalContainer())
            ->add(new TextValue("a"))
            ->add(new TextValue("table"))
        ),

    ["attrs" => ["border" => 1]]
);

//get the builder
$rendererBuilder = new HTMLRendererBuilder();

//provide source of additional attributes
$rendererBuilder->setAttributeContainer($attrs);

//build the renderer
$renderer = $rendererBuilder->buildRenderer();

//check how does this table look rendered in HTML
echo $renderer->renderHTMLBasedOn($tableModel);