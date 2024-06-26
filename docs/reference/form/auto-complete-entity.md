## Auto Complete Entity

Imagine you want to write your own repository method, because the search
results depend not only on a search term but also on other attributes
(e.g. the User, who bought a specific Product). Let\'s call this method
`findByTermAndUser`. This is, how this method could look like:

```php
<?php
namespace App\Repository;

use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;

class ProductRepository extends EntityRepository
{

    public function findByTermAndUser($term, $userId, $limit)
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.user = :userId')
            ->andWhere('p.title = :term')
            ->setParameter('userId', sprintf('%s%%', $userId))
            ->setParameter('title', sprintf('%s%%', $term))
        ;

        $result = $query->getQuery()->getArrayResult();

        $paginator = $this->getPaginator($query);
        $paginator->setMaxPerPage($limit);

        return $paginator;
    }
}
```

That\'s how a standard `Auto-Complete-Entity-Route` looks like:

```yaml
app_product_auto_complete:
    options:
        expose: true
    path: /app/product/auto_complete
    methods: [GET]
    defaults:
        _controller: enhavo_form.controller.auto_complete:searchAction
        _config:
            class: App\Entity\Product
            repository:
                method: findByTerm
```

Maybe you have already noticed the problem. That route only works for
the standard `findByTerm` with the two parameters `$term` and `$limit`.
So how can we add the `$userId`?

The easiest way is to add a flexible value with the userId, we need to
add a value to our path and pick that value to use it as function
argument. To use this value from our route as argument for our
`findByTermAndUser`, the `Route` must have the arguments as follows:

```yaml
app_product_auto_complete:
    options:
        expose: true
    path: /app/product/{userId}/auto_complete
    methods: [GET]
    defaults:
        _controller: enhavo_form.controller.auto_complete:searchAction
        _config:
            class: App\Entity\Product
            repository:
                method: findByTermAndUser
                arguments:
                    - expr:configuration.getSearchTerm()
                    - expr:request.get('userId')
                    - expr:configuration.getLimit()
```

We add all necessary parameters as arguments for our method and use the
`expr:request.get('param')` to get our last missing value. The two other
values are already in our `expr.configuration` and have their own
`Getter-Methods`.

Finally, the last question is, how we add the `$userId` to our path. To
solve this problem, lets add the `$userId` as `route_parameter` to our
`Auto-Complete-Entity-Type` like that:

```php
<?php

namespace App\Form\Type;

use App\Entity\Product;

class ProductType extends AbstractType
{
    use ContainerAwareTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                /** @var User $user */
                $userId = $event->getData()->getId();
                $form = $event->getForm();
                if (!empty($userId) {
                    $form->add('product', AutoCompleteEntityType::class, [
                        'class' => Product::class,
                        'route' => 'app_product_auto_complete',
                        'route_parameters' => [
                            'userId' => $userId
                        ]
                    ]);
                }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_applications_user';
    }
}
```

::: tip Note
Notice, that a `Route-Parameter` can not be `NULL` and that we have
multiple options, to get our `$userId`. The easiest way is to use the
`EventListener` like in our example. For more information go to the
[Symfony Form
Events](https://symfony.com/doc/4.4/form/events.html)-Documentation . If
you want to see the other options, read our and the [Symfony
Documentation](https://symfony.com/doc/current/forms.html) about Forms
in general.
:::
