##  Extend from resource

Sometime you wan\'t add more properties to an enhavo entity. This is
simple done by some configuration. But first of all you have to extend
the class.

```php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Page extends Enhavo\Bundle\PageBundle\Entity\Page
{
    // .. add your properties and mapping information
}
```

### Extend doctrine

Now you have to add mapping information to the
`EnhavoDoctrineExtensionBundle`

```yaml
enhavo_doctrine_extension:
    metadata:
        App\Entity\Page:
            extends: Enhavo\Bundle\PageBundle\Entity\Page
            discrName: 'app'
```

### Change Resource

If the entity you want to extend is a sylius resource (Check it it
implements the `ResourceInterface`). Then you also have to change the
model configuration. If it\'s not a sylius resource you can skip this
step.

```yaml
enhavo_page:
    resources:
        page:
            classes:
                model: App\Entity\Page
```

### Update doctrine interface

In some cases you also have to update the interface information in
doctrine. This is needed for other entities that will refer to your
extended entity.

```yaml
doctrine:
    orm:
        resolve_target_entities:
            Enhavo\Bundle\PageBundle\Model\PageInterface: App\Entity\Page
```
