## Route Resolver

The naming of routes following a convention to prevent configuration (convention over configuration).

### Convention

**Parts**:
- namespace, normally app or the bundle name
- area, e.g. theme or admin
- `api` if it is an api route
- resource name or subsystem
- action

**Examples**:

* app_theme_article_show
* app_api_article_show
* enhavo_page_admin_page_index
* enhavo_page_admin_api_page_index

### Usage

```php
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;

class BookEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly RouteResolverInterface $routeResolver, // [!code focus]
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $createRoute = $this->routeResolver->getRoute('create', ['api' => false]); // [!code focus]
        $updateApiRoute = $this->routeResolver->getRoute('update', ['api' => true]); // [!code focus]
    }
}
```
