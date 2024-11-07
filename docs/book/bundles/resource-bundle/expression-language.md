## Expression Language

### Usage

```php
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;

class BookEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly ResourceExpressionLanguage $resourceExpression, // [!code focus]
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $createRoute = $this->resourceExpression->evalute($options['']); // [!code focus]
        $updateApiRoute = $this->routeResolver->evaluteArray($options[''], [ // [!code focus]
            'resource' => $context->get('resource') // [!code focus]
        ]); // [!code focus]
    }
}

