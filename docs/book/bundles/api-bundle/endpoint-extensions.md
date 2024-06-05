
## Endpoint extensions

The endpoint api uses the type pattern, so it's possible that multiple endpoints create data
for a single request.

From the type pattern we know two possible extensions. One is the parent (vertical) and the other one is the extension
type (horizontal).


### Extend with parent

Extension by parent will only extend one single endpoint. You define the parent by return the FQCN in the `getParentType` function.

```php
namespace App\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SectionEndpointType extends AbstractEndpointType
{
    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $sections = $this->findSections($request);
        $data->set('sections', $sections);
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'sections' => true,
        ]);
    }
    
    public static function getParentType(): ?string
    {
        return BookEndpointType::class;
    }
}
```


### Extend with extension type

The extension type can extend multiple extension at the same time with `getExtendedTypes`

```php
namespace App\Endpoint\Extension;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointTypeExtension;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionExtensionType extends AbstractEndpointTypeExtension
{
    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $sections = $this->findSections($request);
        $data->set('sections', $sections);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'sections' => true,
        ]);
    }

    public static function getExtendedTypes(): array
    {
        return [BookEndpointType::class];
    }
}
```

### Context Data

To communicate between extended endpoints, you can use the `Context` class. Just `set` some data and later
you can `get` the data in the extended type. 

```php{4}
public function handleRequest($options, Request $request, Data $data, Context $context)
{
    $book = $this->findBook($request);
    $context->set('book', $book);
    $data->set('title', $book->getTitle());
}
```

```php{3}
public function handleRequest($options, Request $request, Data $data, Context $context)
{
    $book = $context->get('book');
    $data->set('sections', $book->getSections());
}
```

::: tip Tip
This is often useful if you retrieve an object from e.g. a repository and then normalize it. If you pass it also to the context,
then the next extensions don't have to retrieve it again. This may increase performance as well. 
:::

