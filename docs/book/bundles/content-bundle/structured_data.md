## Structured data

Structured data is information that is organized in a defined format, making it easy for computers (and humans) to store, search, and analyze.
The structured data subsystem helps represent data in the [Schema.org](https://schema.org) format.

With the `StructuredDataManager` and the `getData` function, you can get a normalized array in a json-ld schema.org format, that can 
easily passed to the frontend.

```php

namespace App\Endpoint;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataManager;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Symfony\Component\HttpFoundation\Request;
use Enhavo\Bundle\PageBundle\Repository\PageRepository;

class MyService
{
    public function __construct(
        private StructuredDataManager $structuredDataManager,
        private PageRepository $repository,
    ) {}
    
    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $page = $this->repository->find(1);
        $data->set('structuredData', $this->structuredDataManager->getData($page));
    }
}
```