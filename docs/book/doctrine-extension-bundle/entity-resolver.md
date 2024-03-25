## Entity resolver

The entity resolver is a service to resolve a entity object to a name
and on the other hand you can pass the name with an id to receive this
entity again.

```php
interface EntityResolverInterface
{
    public function getName(object $entity): string;

    public function getEntity(int $id, string $name): ?object;
}
```

Why that is useful? In some cases you need to save the relation to an
entity. But you can\'t serialize it, because this entity might been
updated.

To facing that problem you can just save the name and the id of that
entity a retrieve it later by the entity resolver.

The entity name could simple the class name. But we also use sylius and
therefor the entity class might change because of an update of the
software. So it is useful to save an alias for this entity. The entity
resolver again will take care of that.

### How to use

To use the entity resolver, just inject the service
`Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface`.

```yaml
services:
    my_service:
        arguments:
            - '@Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface'
```
