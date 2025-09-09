## Manager

To create structured data, we just need the `StructuredDataManager`. You can easily inject it as a service.
Here we have an Endpoint

```php
namespace App\Endpoint;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ArticleBundle\Repository\ArticleRepository;
use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataManager;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/show/article')]
class ArticleEndpoint extends AbstractEndpointType
{
    public function __construct(
        private StructuredDataManager $structuredDataManager,
    ) {}

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $article = $this->getArticle($request);
        $data->set('structuredData', $this->structuredDataManager->getData($article));
    }
    
    public function getArticle(Request $request) 
    {
        // receive article from request, e.g. use a repository
    }
}
```