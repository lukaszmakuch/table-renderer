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
 *  _
 * |a|
 * |_|
 * |b|
 * |_|
 * |c|
 * |_|
 * </pre>
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class HorizontalContainer extends Container
{
    public function getHeight()
    {
        return !empty($this->elements)
            ? array_reduce($this->elements, function ($summaryHeight, Element $element) {
                return $summaryHeight + $element->getHeight();
            }, 0)
            : 1;
    }

    public function getWidth()
    {
        return !empty($this->elements) ? $this->elements[0]->getWidth() : 1;
    }

    public function setHeight($height)
    {
        $ratio = $height / $this->getHeight();
        foreach ($this->elements as $element) {
            $element->setHeight($element->getHeight() * $ratio);
        }
        
        $this->notifyObservers();
    }

    public function setWidth($width)
    {
        foreach ($this->elements as $element) {
            $element->setWidth($width);
        }
        
        $this->notifyObservers();
    }

    public function accept(TreeVisitor $visitor)
    {
        $visitor->visitHorizontalContainer($this);
    }
}
