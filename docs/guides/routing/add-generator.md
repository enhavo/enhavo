## Add generator

You can add your own generator class. Create a class a extend from
`Enhavo\Bundle\RoutingBundle\AutoGenerator\AbstractGenerator`.

```php
<?php

namespace App\Routing\Generator;

use Enhavo\Bundle\RoutingBundle\AutoGenerator\AbstractGenerator;

class NameGenerator extends AbstractGenerator
{
    public function generate($resource, $options = [])
    {
        $path = sprintf('/%s', strtolower($resource->getName()));
        $route->setStaticPrefix($path);
    }

    public function getType()
    {
        return 'name';
    }
}
```

Now add a service for your generator.

```yaml
App\Routing\Generator\NameGenerator:
     public: true
     calls:
         - [setContainer, ['@service_container']]
     tags:
         - { name: 'enhavo_route.generator', alias: 'name' }
```
