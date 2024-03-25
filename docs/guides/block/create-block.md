## Create block

[Use make command](../../reference/block/index)

### Create model

tbc.

### Create factory

tbc.

### Create form

tbc.

### Create template

tbc.

### Add configuration

tbc.

### Create type

First you have to define a class that implements the `BlockInterface`.
On that interface you have to provide a `getType`. This is the type name
we use later display this block. If you want to have some additional
helper methods we recommend to extend from `AbstractType` that provide
some helpers like `renderTemplate`.

```php
<?php

namespace AppBundle\Block;

use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;

class SliderBlock extends AbstractBlockType
{
    public function render($parameters)
    {
        $this->renderTemplate('AppBundle:Block:google_analytics.html.twig');
    }

    public function getType()
    {
        return 'google_analytics';
    }
}
```

Now you have to add the created class to the dependency injection
container.

```yaml
app.block.google_analytics:
    class: AppBundle\Block\GoogleAnalyticsBlock
    calls:
        - [setContainer, ['@service_container']]
    tags:
        - { name: enhavo.block }
```
