## Api documentation

With the Api bundle, you can define different sections through configuration and use describers to get an
OpenAPI-compliant specification.


By default, the Api bundle comes with a default section that uses a `RouteDescriber` 

```yaml
enhavo_api:
    documentation:
        section:
            default:
                Enhavo\Bundle\ApiBundle\Documentation\RouteDescriber: ~

```

You can add other describers in your application configuration.

```yaml
enhavo_api:
    documentation:
        section:
            default:
                Enhavo\Bundle\ApiBundle\Documentation\InfoDescriber:
                    title: 'Application api'
                Enhavo\Bundle\ApiBundle\Documentation\ServersDescriber:
                    servers: 
                        - url: https://my-domain.tld
                          description: Testing api
```

Create your own describer and implement the `Enhavo\Bundle\ApiBundle\Documentation\DescriberInterface` interface.

### Describe endpoints

Endpoints have a `describe` method to create an OpenAPI-compliant specification. Use the build in api to describe your endpoint.

```php
namespace App\Endpoint;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;

class MyEndpoint extends AbstractEndpointType 
{
    // ...

    public function describe($options, Path $path): void
    {
        $path->method('get')
            ->parameter('id')
                ->in('path')
                ->description('The id of your entity')
                ->schema()
                    ->string()->end()
                ->end()
            ->end()
            ->response('200')
                ->description('Data')
                    ->content()
                        ->schema()
                            ->object()
                                ->property('id', 'string')
    }
}
```

To add a route to a section, you need to add a `_describe` option.

```yaml
app_book:
    path: /book/{slug}
    defaults:
        _describe: true # use true for default or a string for other sections
        _endpoint:
            type: App\Endpoint\BookEndpointType
```

### Show specification

You can use the command `enhavo:api:create-docs` to create a yaml specification.

```bash
$ bin/console enhavo:api:create-docs specification.yaml
```

Or add routes to expose the specification in a swagger like style.

```yaml
api_docs:
    path: /docs
    defaults:
        _controller: Enhavo\Bundle\ApiBundle\Controller\DocumentationController::indexAction
        data_route: api_docs_data

api_docs_data:
    path: /docs/data
    defaults:
        _controller: Enhavo\Bundle\ApiBundle\Controller\DocumentationController::dataAction
        section: default
```
