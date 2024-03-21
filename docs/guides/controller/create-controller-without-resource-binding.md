## Create controller without resource binding

You can also create a controller, that act like a resource controller,
but it doesn\'t depend on a concrete resource. You just need to create a
controller that extends from `AppController`.

```php
namespace ProjectBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\AppController;

class MyAppController extends AppController {
    //...
}
```

After this you need to inject the services over the dependency injection
and make the controller as a service in the `service.yml`

```yaml
services:
    project.controller.my_app:
        class: ProjectBundle\Controller\MyAppController
        arguments:
            - '@sylius.resource_controller.request_configuration_factory'
            - '@viewer.factory'
            - '@fos_rest.view_handler'
        calls:
            - [setContainer, ['@service_container']]
```

