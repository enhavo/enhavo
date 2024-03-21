## Create Form

### Creating a Form

After we created our entity now we want to create our form, so later we
can edit it in the admin interface. For this task, we need to build a
form, which translate data from an object to a in HTML printable form,
so a user can modify this data. After this modification, submitted by
the user, this data has to be validated and, if possible, converted back
to a data object.

At the beginning, focus on this Product class we´ve created in the
chapter Create Entity before.

Symfony comes with many built-in types which are listed on this page
<https://symfony.com/doc/4.2/forms.html#forms-type-reference> and also
Enhavo offers a variety of build-in forms specially new designed or
customized for Enhavo.

Usually form are stored under `src/Form/Type`

```php
<?php
// src/Form/Type/ProductType.php

namespace App\Form\Type;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'Title'
            ))
            ->add('price', CurrencyType::class, array(
                'label' => 'Price'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => Product::class,
        ));
    }
}
```

Creating a form with the FormBuilder is quite comfortable and requires
relatively less code. With the FormBuilder, we just have to specify some
important properties and individual settings for each property of our
Product object, and it will do the hard work for us: building the form.

We know, that the Product object has two properties (title and price),
so we add two fields with these properties to our form. We also set a
type (e.g. TextType and CurrencyType) which determines which kind of
HTML form tag(s) will be rendered for that field.

As third parameter the `add` method from the FormBuilderInterface
expects an array. Inside this array, we can assign individual values for
many options. Build-in Symfony Form Types have different numbers and
types of options depending on its functionality. In our example we
defined custom labels for each Form Field.

Symfony comes with many built-in types which are listed on this
[page](https://symfony.com/doc/4.2/forms.html#forms-type-reference.).

As an example of available options here a link to the options of the
very common
[TextType](https://symfony.com/doc/4.2/reference/forms/types/text.html)

Even it´s not always (but very often) necessary, it´s generally a good
practice to specify the data_class, which represents the class that
holds the underlying data (e.g. AppEntityProduct).

### Creating a Service

Now, when our separate form class is complete, we´re close to success.
The last thing we have to do is to create a service (if you´ve never
heard about services/Dependency Injection, check the [Symfony Service
Container
Documentation](https://symfony.com/doc/current/service_container.html))
for our form, so we can use it everywhere in our application.

First, we create a yaml-file in the directory
[config/services/forms.yaml]{.title-ref}\`, for this and all future form
services. Of course, you can name it completely different e.g.
my_services.yml and you can also use one yaml-file for many different
service-types, but in many applications, we will need a lot of form
services, so it is a good practice to separate services by type in their
own files.

```yaml
# config/services/forms.yaml
services:
    App\Form\Type\ProductType:
        tags:
            - { name: form.type }
```

To tell your application something about your new separate service yaml
file, we have to import it inside our `config/services.yaml` file with
this simple line at the end of the file:

```yaml
# config/services.yaml
parameters:
    locale: 'en'

services:
    _defaults:
        # Automatically injects dependencies in your services.
        autowire: true
        # Automatically registers your services as commands, event subscribers, etc.
        autoconfigure: true

# add this lines to your file
imports:
    - { resource: services/forms.yaml }
```

### Final words

That's it! We ´ve created a simple symfony form and service in the most
flexible way, so we can use it everywhere in our application and reuse
it as often as we like. In the next chapter, we will see, how all
previous developed parts of our application can be connected and be part
of our first Enhavo resource.
