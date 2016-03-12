<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer;

/**
 * Test atomic cell values.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class AtomicCellValuesTest extends \PHPUnit_Framework_TestCase
{
    public function testTextValue()
    {
        $textValue = new TextValue("abc");
        $this->assertEquals("abc", $textValue->getText());
    }
}
