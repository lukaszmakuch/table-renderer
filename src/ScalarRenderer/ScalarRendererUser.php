<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\ScalarRenderer;

/**
 * Uses the whole scalar renderer.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ScalarRendererUser
{
    /**
     * @param \lukaszmakuch\TableRenderer\ScalarRenderer\ScalarRenderer $renderer
     * @return null
     */
    public function setScalarRenderer(ScalarRenderer $renderer);
}