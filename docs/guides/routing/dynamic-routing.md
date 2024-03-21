## Dynamic routing

The `EnhavoRoutingBundle` is used to add optional dynamic routing for
the resources, so user are able to enter their own url. If you want to
generate a url path and the static routing can\'t fit your needs (e.g.
nested paths) you may go for dynamic routing as well.

This following example will show you how to add a url field to your
entity and how you map it to a controller action.

### Relation

First we need to tell our application that an entity has a `oneToOne`
relation to a route.

```yaml
oneToOne:
    route:
        cascade: ['persist', 'refresh', 'remove']
        targetEntity: Enhavo\Bundle\RoutingBundle\Model\RouteInterface
        joinColumn:
            onDelete: CASCADE
```

Or if you use annotations

```php
/**
 * @var RouteInterface
 *
 * @ORM\OneToOne (targetEntity="Enhavo\Bundle\RoutingBundle\Model\RouteInterface", cascade={"persist", "remove", "refresh"})
 * @ORM\JoinColumn (onDelete="CASCADE")
 */
private $route;
```

Implement the `Routeable` interface for your class.

```php
<?php

use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;

class Page implements Routeable
{

    /**
     * @var RouteInterface
     */
    private $route;

    /**
     * @return RouteInterface
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param RouteInterface $route
     */
    public function setRoute(RouteInterface $route)
    {
        $this->route = $route;
    }

    //...
}
```

### Form

To add an url field in our form we just use the `RouteType`.

```php
<?php

use Enhavo\Bundle\RoutingBundle\Form\Type\RouteType;

// ...

public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder->add('route', RouteType::class);

    // ...
}
```

If you render your form manually, you shouln\'t forget to add it in your
template file.

```twig
{{ form_row(form.route) }}
```

If you use the form in the enhavo admin, no further steps are required,
because enhavo will take care to call the `Generator` and routines to
update the route properly.

### Controller

And last but not least, we have to define our controller, and add some
mapping information to the `SymfonyCMF RoutingBundle` which is used by
the `EnhavoRoutingBundle`. The mapping contains the class name of our
entity and the controller action which should be called.

```yaml
cmf_routing:
    dynamic:
        controllers_by_class:
            App\Entity\Page: App\Controller\PageController:showAction
```

Add the action to your controller. The parameter must named
`$contentDocument` and will be your entity.

```php
<?php

class PageController
{
    public function showAction(Page $contentDocument)
    {
        return $this->render('page/show.html.twig', [
            'page' => $contentDocument
        ]);
    }
}
```

### Add controller information to route

Like in static routes, it is also possible to save the linked controller
to the route itself. You can write a `Generator` and store the
information

```php
$page->getRoute()->setDefaults([
    '_controller' => 'App\Controller\PageController:showAction'
])
```
