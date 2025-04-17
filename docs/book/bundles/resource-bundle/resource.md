## Resource

Resources are commonly standalone entities or sometimes called root entities. We use alias as abstraction for a resource,
that helps us to later change the concrete model.

### Register

Simple we add a `book` resource, where model is mandatory and factory and repository is optional.

```yaml
# config/resources/book.yaml
enhavo_resource:
    resources:
        app.book: # resource name
            classes:
                model: App\Entity\Book
                factory: App\Factory\BookFactory
                repository: App\Repository\BookRepository
            label: Book
            translation_domain: ~
            priority: 10
```

This will create us a factory service with name `app.book.factory` and a repository service `app.book.repository`, that can be injected
to any other service if necessary. If you don't define a factory or repository, 
a default ones with these classes `Enhavo\Bundle\ResourceBundle\Factory\Factory` and `Enhavo\Bundle\ResourceBundle\Repository\EntityRepository` will be created.

To autowire repositories and factories, default binds are created as well. For the `app.book` example, 
you need to name the parameter `$bookRepository`, `$bookFactory` or `$appBookRepository`, `$appBookFactory`. 
The parameter type **must** match the configured ones (Don't use interfaces here).


::: warning
A model can applied only once to a resource name and the repository class will always overwrite the repository class
in your doctrine meta configuration!
:::

If more configurations for the same resources name are defined, the one with higher `priority` will overwrite lower ones.

Give the resource name also a `label`, to display the name wherever it will be needed.

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
        $repository = $this->resourceManager->getRepository('app.book');
        $book = $repository->find($request->get('id'));
        
        // or factory
        $factory = $this->resourceManager->getFactory('app.book');
        $book = $factory->createNew();
        
        // update entity and save
        $book->setTitle($request->get('title'));
        $this->resourceManager->save($book);
        
        // or duplicate
        $otherBook = $this->resourceManager->duplicate($book);
        
        // or validate
        $otherBook = $this->resourceManager->validate($book);
        
        // or apply transition
        $otherBook = $this->resourceManager->applyTransition($book, 'publish', 'book_graph');
        
        // or delete
        $this->resourceManager->delete($book);
        
        // or get metadata
        $metadata = $this->resourceManager->getMetadata($book);
        $label = $metadata->getLabel()

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
        return [
            ResourceEvents::PRE_CREATE => 'hook',
            ResourceEvents::POST_CREATE => 'hook',
            ResourceEvents::PRE_UPDATE => 'hook',
            ResourceEvents::POST_UPDATE => 'hook',
            ResourceEvents::PRE_DELETE => 'hook',
            ResourceEvents::POST_DELETE => 'hook',
        ];
    }

    public function hook(ResourceEvent $event)
    {
        $resource = $event->getSubject();
        
        // do something here
    }
}
```

### Duplicate

With duplicate, you can create deep clones of a resource. To know which properties need to be copied, 
you have to mark up them. This is normally done by the Attribute `Duplicate` but you can also use yaml
configuration.


::: code-group

```php [Attribute]
<?php
namspace App\Entity;

use Enhavo\Bundle\ResourceBundle\Attribute\Duplicate;
use Doctrine\Common\Collections\Collection;

class Book
{
    #[Duplicate('string', ['postfix' => ' Copy!!', 'group' => ['duplicate']])]
    private ?string $name = null;

    #[Duplicate('model', [
        'group' => ['duplicate']
    ])]
    private Collection $chapters = null;
}
```

```yaml [YAML]
enhavo_resources:
    duplicate:
        App\Entity\Book:
            name:
                type: string
                postfix: ' Copy!!'
                group: ['duplicate']
            chapters:
                type: model
                group: ['duplicate']
```

:::

Use the manager to duplicate a resource. The returned resource is not saved yet. Use the `save` method to make it persistent.

```php
$otherBook = $this->resourceManager->duplicate($book, null, ['group' => 'duplicate']);
$this->resourceManager->save($otherBook);
```

It is also possible to duplicate a resource into a target resource. The reference of the target keeps the same,
but the values will be changed to the one from the source resource.

```php
$target = $this->resourceManager->duplicate($source, $target, ['group' => 'duplicate']);
````

On the [duplicate reference section](/reference/duplicate/index), you can find the possible duplicate types.

### State Machine

The resource bundle uses the winzou state machine. Here is a small graph example. 
For more documentation read the [docs](https://github.com/winzou/StateMachineBundle).


```yaml
winzou_state_machine:
    app.book:
        class: App\Entity\Book
        property_path: state
        graph: book_graph
        states:
            - new
            - published
        transitions:
            publish:
                from: [new]
                to: published
        # hooks
        callbacks:
            before:
                update_publish_date:
                    on:   'publish'
                    do:   ['@book.manager', 'updatePublishDate']
                    args: ['object']
```

Use `canApplyTransition` to check if a transition can be applied or `applyTransition` to execute the transition with it callbacks and save the state.

```php
if ($this->resourceManager->canApplyTransition($book, 'publish', 'book_graph')) {
    $otherBook = $this->resourceManager->applyTransition($book, 'publish', 'book_graph');
}
```

::: warning
The save hook will not be triggered if you use applyTransition
:::

