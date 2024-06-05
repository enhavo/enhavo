# Area

## Why we use areas?

Areas helps you to reuse your endpoints and focus on the business logic. So what are areas exactly and how we use them.
Imagine you want to build a registration form. With endpoints you can write a single class to show the form and make it also available as an api.

```php
namespace App\Endpoint;

use App\Form\RegisterForm;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/register', name: 'app_register', defaults: ['_format' => 'html'])]
#[Route(path: '/api/register', defaults: ['_format' => 'json'])]
class RegisterEndpointType extends AbstractEndpointType
{
    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $form = $this->createForm(RegisterForm::class);
        
        $form->handeRequest($request)
        if ($form->isSubmitted() && $form->valid()) {
            // ... register the user here
        }
        
        $data->set('form', $this->normalize($form, null, ['groups' => 'endpoint']));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => 'theme/endpoint/register.html.twig'
        ]);
    }

    public static function getParentType(): ?string
    {
        return ViewEndpointType::class;
    }
}
```

But in your template you may want to show a navigation, or want to pass translation data. So you will change your `configureOptions`

```php
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => 'theme/endpoint/register.html.twig',
            'navigation' => ['main', 'footer'], // [!code ++]
            'translation' => true, // [!code ++]
        ]);
    }
```

So the navigation and translation data will also be passed in the request, that's what we want, right?
Not exactly, the problem is, that our api will also serve the navigation and translation data.

```json
{
    "form" : { "...": "..." },
    "navigation": { "...": "..." },
    "translation": { "...": "..." }
}
```

We only want to turn on the navigation and translation option if we are on `/register` but not on `/api/register`.
That's where areas are coming in. An area define a set of urls, where we can change the behaviour of your endpoints.
This means we are not only restricted to options, but this is the most common use case.

## Configure areas

Areas applies on urls. With the `path` option you can add any url or use regex. It is also possible to pass an array of urls.
With `firewall` you can also refer a security firewall to avoid same url configuration on different places. 
The `firewall` and `path` configuration can be combined as well. The first area that matches will be applied.
The `endpoint` settings are the predefined options when an endpoint is used. 

```yaml
enhavo_app:
    area:
        api: # name of the area
            path: '^/api/.*'
            options:
                format: 'json'
        theme:
            firewall: main
            options:
                navigation: ['theme', 'endpoint']
                routes: ['theme']
                vue_routes: ['theme']
                translation: ['javascript']
                format: 'html'
        admin:
            path: 
              - '^/api/admin/.*'
              - '^/admin/api/.*'
```

::: tip
If an option is set in a route, then the area configuration will not overwrite that specific option.
:::

## Beyond options

```yaml
enhavo_user:
	area:
		theme:
            reset_password_confirm:
                template: admin/user/reset-password/confirm.html.twig
                auto_login: true
                redirect_route: enhavo_app_index
                form:
                     class: Enhavo\Bundle\UserBundle\Form\Type\ResetPasswordType
                     options:
                         validation_groups: ['reset_password']
```