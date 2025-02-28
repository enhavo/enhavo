## Normalization

Data, that passes to an endpoint must be normalized first. Symfony has therefor the serializer component. You can just
call the `normalize` function that comes with the endpoint. We advise to use serialization groups, to have more control
to what will be normalized and to prevent circular normalization due bidirectional or circular references.

```php
namespace App\Endpoint;

class BookEndpointType extends AbstractEndpointType
{
    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $book = $this->findBook($request);
        $data->set('book', $this->normalize($book, [], ['groups' => 'endpoint']));
    }
}
```

We recommend to use attributes to define which properties should be normalized.

```php
namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

class Book
{
    #[Groups(['endpoint'])]
    public ?string $title = null;
    
    #[Groups(['endpoint'])] // also possible on functions
    public getAuthor(): string
    {
        // ...
    }
}
```

Read more about normalization in the [symfony docs](https://symfony.com/doc/current/serializer.html)


### Data Normalizer

The ApiBundle also provide another option to add data to the normalizer. You can use `DataNormalizer` classes, that 
can easily bind to classes or interfaces and add additional data. 


```php
namespace App\Normalizer;

use App\Entity\Book;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Normalizer\AbstractDataNormalizer;
use Symfony\Component\Routing\RouterInterface;

class BookNormalizer extends AbstractDataNormalizer
{
    public function __construct(
        private RouterInterface $router,
    ) {}

    public function buildData(Data $data, $object, string $format = null, array $context = []): void
    {
        if (!$this->hasSerializationGroup('endpoint', $context)) {
            return;
        }
        
        $data->set('url', $this->router->generate('app_book', $object->getTitle()));
        $data->set('chapter', $this->normalize($book->getChapters(), [], ['groups' => 'chapter']));
    }

    public static function getSupportedTypes(): array
    {
        return [Book::class];
    }
}
```

The data normalizer is useful if ...

* you need a service to generate additional data
* you don't have this property or function in your class to normalize
* you want to add data to all classes using an interface
* you want to add data regardless to serialization groups
* you want to call a different serialization group for a property

