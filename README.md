[![travis](https://travis-ci.org/lukaszmakuch/table-renderer.svg)](https://travis-ci.org/lukaszmakuch/table-renderer)
# Table renderer
Allows to render tree structures as tables.
## HTML example
[![alt text](http://lukaszmakuch.pl/misc/table_renderer_one_step.jpg "Tree structure compared to an HTML structure")](http://lukaszmakuch.pl/misc/table_renderer.jpg)
Click the image for more examples.
## Trees
### Elements
There are 3 main elements of every tree:
#### Atomic values
They are indivisible.
##### TextValue
It simply holds some text which will be rendered inside some cell.
```php
use lukaszmakuch\TableRenderer\TextValue;

$flowers = new TextValue("roses");
```
#### Containers
They hold other elements.
##### HorizontalContainer
Its elements are on top of each other.
```php
use lukaszmakuch\TableRenderer\HorizontalContainer;
use lukaszmakuch\TableRenderer\TextValue;

$column = (new HorizontalContainer())
    ->add(new TextValue("top"))
    ->add(new TextValue("bottom"));
```
##### HorizontalContainer
Its elements are next to each other.
```php
use lukaszmakuch\TableRenderer\VerticalContainer;
use lukaszmakuch\TableRenderer\TextValue;

$row = (new VerticalContainer())
    ->add(new TextValue("left"))
    ->add(new TextValue("right"));
```
### Building trees
It's possible to build a composite of any complexity.
```php
use lukaszmakuch\TableRenderer\HorizontalContainer;
use lukaszmakuch\TableRenderer\VerticalContainer;
use lukaszmakuch\TableRenderer\TextValue;

$table = (new HorizontalContainer())
    ->add((new VerticalContainer())
        ->add(new TextValue("top left"))
        ->add(new TextValue("top middle"))
        ->add(new TextValue("top right"))
    )
    ->add((new VerticalContainer())
        ->add(new TextValue("bottom left"))
        ->add(new TextValue("bottom right"))
    );
```
## Renderers
Allows to render tables based on tree structures.

### HTMLRenderer
Renders HTML code.
#### Getting the renderer
```php
use lukaszmakuch\TableRenderer\HTMLRenderer\HTMLRendererBuilder;

$builder = new HTMLRendererBuilder();
$htmlRenderer = $builder->buildRenderer();
```
#### Basic usage
```php
use lukaszmakuch\TableRenderer\VerticalContainer;
use lukaszmakuch\TableRenderer\TextValue;
use lukaszmakuch\TableRenderer\HTMLRenderer\HTMLRenderer;

$tree = (new VerticalContainer())
    ->add(new TextValue("left"))
    ->add(new TextValue("right"));

/* @var $renderer HTMLRenderer */
echo $renderer->renderHTMLBasedOn($tree);
```

#### Adding HTML attributes
It's possible to assign HTML attributes to atomic values as well as to a whole table.
[ObjectAttributeContainer](https://github.com/lukaszmakuch/object-attribute-container) is used to achieve that.
##### Building renderer with support of additional attributes
First, you need to build the renderer with some attribute container.
```php
use lukaszmakuch\TableRenderer\HTMLRenderer\HTMLRendererBuilder;
use lukaszmakuch\ObjectAttributeContainer\Impl\ObjectAttributeContainerImpl;

//source of attributes
$attrs = new ObjectAttributeContainerImpl();

//building with the attribute container
$builder = new HTMLRendererBuilder();
$builder->setAttributeContainer($attrs);

$htmlRenderer = $builder->buildRenderer();
```
##### Adding attributes to a tree.
```php
use lukaszmakuch\ObjectAttributeContainer\ObjectAttributeContainer;
use lukaszmakuch\TableRenderer\VerticalContainer;
use lukaszmakuch\TableRenderer\TextValue;
lukaszmakuch\TableRenderer\HTMLRenderer\HTMLRenderer;

/* @var $attrs ObjectAttributeContainer */

//table with border 1
$tree = $attrs->addObjAttrs(

    (new VerticalContainer())

        //cell with no extra style
        ->add(new TextValue("left"))

        //cell with red text
        ->add($attrs->addObjAttrs(
            new TextValue("right"),
            ["attrs" => ["style" => "color: #f00"]]
        )),

    ["attrs" => ["border" => 1]]
);

/* @var $renderer HTMLRenderer */
echo $renderer->renderHTMLBasedOn($tree);
```
#### Support of custom atomic values
This renderer supports custom atomic values.
A custom value should extend [AtomicCellValue](src/AtomicCellValue.php) and be supported by an instance of [AtomicValueRenderer](src/HTMLRenderer/AtomicValueRenderer/AtomicValueRenderer.php).
Then you can register a new atomic value renderer like that:
```php
use lukaszmakuch\TableRenderer\HTMLRenderer\HTMLRendererBuilder;

/* @var @builder HTMLRendererBuilder */
$builder->addAtomicValueRenderer(
    NewAtomicType::class,
    new NewAtomicTypeRenderer()
);
```

### ScalarRenderer
It renders models of tables as scalar values or arrays of scalar values (or other arrays).
#### Getting the renderer
```php
use lukaszmakuch\TableRenderer\ScalarRenderer\ScalarRendererBuilder;

$renderer = (new ScalarRendererBuilder())->build();
```
#### Basic usage
```php
use lukaszmakuch\TableRenderer\VerticalContainer;
use lukaszmakuch\TableRenderer\TextValue;
use lukaszmakuch\TableRenderer\ScalarRenderer\ScalarRenderer;

$tree = (new VerticalContainer())
    ->add(new TextValue("first"))
    ->add(new TextValue("second"));

/* @var $renderer ScalarRenderer */
var_dump($renderer->getScalarRepresentationOf($tree));
//[
//    'type' => 'vertical-container',
//    'value' => [
//        ['type' => 'text', 'value' => 'first'],
//        ['type' => 'text', 'value' => 'second']
//     ]
//]
```
#### Support of custom elements
This renderer supports custom elements.
A custom element should extend [AtomicCellValue](src/AtomicCellValue.php) and be supported by an instance of [ScalarRenderer](src/ScalarRenderer/ScalarRenderer.php).
Then you can register a new element renderer like that:
```php
use lukaszmakuch\TableRenderer\ScalarRenderer\ScalarRendererBuilder;

/* @var @builder ScalarRendererBuilder */
$builder->addRenderer(
    NewAtomicType::class,
    new NewAtomicTypeRenderer()
);
```

## Examples
Check examples in the [examples directory](examples/)
## Installation
Use [composer](https://getcomposer.org) to get the latest version:
```
$ composer require lukaszmakuch/table-renderer
```
