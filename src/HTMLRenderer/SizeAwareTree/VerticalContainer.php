<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree;

/**
 * Holds elements like that:
 * <pre>
 *  _____
 * |a|b|c|
 * |_|_|_|
 * <
 * /pre>
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class VerticalContainer extends Container
{
    public function getHeight()
    {
        return !empty($this->elements) ? $this->elements[0]->getHeight() : 1;
    }

    public function getWidth()
    {
        return !empty($this->elements)
            ? array_reduce($this->elements, function ($summaryWidth, Element $element) {
                return $summaryWidth + $element->getWidth();
            }, 0)
            : 1;
    }

    public function setHeight($height)
    {
        foreach ($this->elements as $element) {
            $element->setHeight($height);
        }

        $this->notifyObservers();
    }

    public function setWidth($width)
    {
        $ratio = $width / $this->getWidth();
        foreach ($this->elements as $element) {
            $element->setWidth($element->getWidth() * $ratio);
        }
        
        $this->notifyObservers();
    }
    
    public function accept(TreeVisitor $visitor)
    {
        $visitor->visitVerticalContainer($this);
    }
}
