<?php

/**
 * This file is part of the TableRenderer library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\TableRenderer\ScalarRenderer;

use lukaszmakuch\PropertySetter\SettingStrategy\CallOnlyMethodAsSetter;
use lukaszmakuch\PropertySetter\SilentPropertySetter;
use lukaszmakuch\PropertySetter\SimplePropertySetter;
use lukaszmakuch\PropertySetter\TargetSpecifier\PickByClass;
use lukaszmakuch\PropertySetter\ValueSource\UseDirectly;
use lukaszmakuch\TableRenderer\HorizontalContainer;
use lukaszmakuch\TableRenderer\ScalarRenderer\Exception\UnableToBuild;
use lukaszmakuch\TableRenderer\ScalarRenderer\Impl\ClassBasedProxy;
use lukaszmakuch\TableRenderer\ScalarRenderer\Impl\ContainerRenderer;
use lukaszmakuch\TableRenderer\ScalarRenderer\Impl\TextRenderer;
use lukaszmakuch\TableRenderer\TableElement;
use lukaszmakuch\TableRenderer\TextValue;
use lukaszmakuch\TableRenderer\VerticalContainer;

/**
 * Builds scalar renderer of table models.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ScalarRendererBuilder
{
    /**
     * @var ScalarRenderer[] like String (supported class => ScalarRenderer its renderer
     */
    private $actualRenderers = [];

    public function __construct()
    {
        $this->addRenderer(TextValue::class, new TextRenderer());
        $this->addRenderer(
            VerticalContainer::class, 
            new ContainerRenderer("vertical-container")
        );
        $this->addRenderer(
            HorizontalContainer::class,
            new ContainerRenderer("horizontal-container")
        );
    }

    /**
     * @param String $supportedClass
     * @param ScalarRenderer $prototypeOfItsRenderer
     * 
     * @return ScalarRendererBuilder self
     */
    public function addRenderer(
        $supportedClass,
        ScalarRenderer $prototypeOfItsRenderer
    ) {
        $this->actualRenderers[$supportedClass] = $prototypeOfItsRenderer;
        return $this;
    }

    /**
     * @return ScalarRenderer
     * @throws UnableToBuild
     */
    public function build()
    {
        $renderer = new ClassBasedProxy();
        $actualRenderer = new ClassBasedProxy();
        $renderer->registerRenderer(TableElement::class, $actualRenderer);
        $dependencySetter = new SilentPropertySetter(new SimplePropertySetter(
            new PickByClass(ScalarRendererUser::class),
            new CallOnlyMethodAsSetter(ScalarRendererUser::class),
            new UseDirectly($renderer)
        ));
        foreach ($this->actualRenderers as $supportedClass => $itsRenderer) {
            $atomicValRenderer = clone $itsRenderer;
            $dependencySetter->setPropertiesOf($atomicValRenderer);
            $actualRenderer->registerRenderer(
                $supportedClass,
                $atomicValRenderer
            );
        }

        return $renderer;
    }
}
