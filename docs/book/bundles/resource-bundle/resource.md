## Resource

Resources are commonly standalone entities or sometimes called root entities. We use alias as abstraction for a resource,
that helps us to later change the concrete model.

### Register

Simple we add a `book` resource, where model is mandatory and factory and repository is optional.

```yaml
# config/packages/enhavo_resource.yaml
enhavo_resource:
    resources:
        book:
            classes:
                model: App\Entity\Book
                factory: App\Factory\BookFactory
                repository: App\Repository\BookRepository
```

This will create us a factory service with name `book.factory` and a repository service `book.repository`, that can be injected
to any other service if necessary.


### ResourceManager

The `ResourceManager` keep all factory and repository services. So you can retrieve the services with the alias from it.
The factory is used to create a new entity while the repository to find an existing one. Use the `save` method to
save the entity.

```php
namespace App\Endpoint;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;

class BookEndpoint extends AbstractEndpointType
{
    public function __construct(
        private ResourceManager $resourceManager,
    ) {}

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        // repository
        $repository = $this->resourceManager('book')->getRepository();
        $book = $repository->find($request->get('id'));
        
        // or factory
        $factory = $this->resourceManager('book')->getFactory();
        $book = $factory->createNew();
        
        // update entity and save
        $book->setTitle($request->get('title'));
        $this->resourceManager->save($book);
        
        // or delete
        $this->resourceManager->delete($book);
    }
}
```

As you can see in the above example, we don't need the doctrine entity manager here. This is encapsulated in the `ResourceManager`.

### Hooks

The `save` method of the `ResourceManager` also fires some hooks we can catch with an event listener or subscriber.


```php
class BookSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            ResourceEvents::PRE_CREATE => 'hook',
            ResourceEvents::PRE_UPDATE => 'hook'
            'book.pre_create' => 'hook',
            'book.post_create' => 'hook',
        );
    }

    public function hook(ResourceEvent $event)
    {
        $resource = $event->getSubject();
        
        // do somthing here
    }
}
```

### State Machine

tbc.



