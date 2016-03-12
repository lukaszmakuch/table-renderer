<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\HTMLRenderer\SizeAwareTree;

class HorizontalContainerFactory extends ContainerFactory
{
    public function buildContainer()
    {
        return new HorizontalContainer($this->synchronizerFactory);
    }
}
