## Define type system

To define a new type system, we need a concrete class, that uses our types, and add type compiler that collects it. 
Later we add types and use them.

### Concrete class and type interface

Let's imagine we want to make a new type system for actions, where we can pass data to the frontend and
execute on the server. So we need to define a `getViewData` and an `execute` function.

```php

namespace App\Action;

class Action extends AbstractTypeContainer
{
    public function getViewData(): array
    {
        
    }
    
    public function execute($resource): void
    {
    
    }
}
```

When we create a type interface, we may have a hierarchy of types, so instead of return the view data, we let it build.

```php

namespace App\Action;

use Enhavo\Component\Type\TypeInterface;

interface ActionTypeInterface extends TypeInterface
{
    public function buildViewData(array $options, &$data): void;

    public function execute(array $options, $resource): void;
}
```

So we can call our types in the concrete class now.

```php

namespace App\Action;

/** // [!code ++]
 * @property ActionTypeInterface $type // [!code ++]
 * @property ActionTypeInterface $parents // [!code ++]
 */ // [!code ++]
class Action extends AbstractTypeContainer
{
    public function getViewData(): array
    {
        $data = []; // [!code ++]
        foreach ($this->parents as $parent) { // [!code ++]
            $parent->buildViewData($this->options, $data); // [!code ++]
        } // [!code ++]
        $this->type->buildViewData($this->options, $data); // [!code ++]
        return $data; // [!code ++]
    }
    
    public function execute($resource): void
    {
        $this->type->execute($this->options, $resource); // [!code ++]
    }
}
```

### Type compiler

We want to name our type system `Action`. The factory is very generic, so we add a type compiler that add the factory as a service
and auto collects our types with the tag `action`. 

You need to add the type compiler at the kernel or bundle `build` function.

::: code-group

```php [Kernel]
namespace App;

use App\Action\Action;
use Enhavo\Component\Type\TypeCompilerPass;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    protected function build(ContainerBuilder $container): void // [!code ++]
    { // [!code ++]
        parent::build($container); // [!code ++]
        $container->addCompilerPass(new TypeCompilerPass('Action', 'action', Action::class)); // [!code ++]
    } // [!code ++]
}
```

```php [Bundle]
namespace Bundle\MyBundle;

use App\Action\Action;
use Enhavo\Component\Type\TypeCompilerPass;

class MyBundleBundle extends Bundle
{
    public function build(ContainerBuilder $container): void // [!code ++]
    { // [!code ++]
        parent::build($container); // [!code ++]
        $container->addCompilerPass(new TypeCompilerPass('Action', 'action', Action::class)); // [!code ++]
    } // [!code ++]
}
```

:::

If you work with auto wiring you can add the action tag automatically to the interface.

```php
public function build(ContainerBuilder $container): void 
{            
    parent::build($container); 
    $container->addCompilerPass(TypeCompilerPass('Action', 'action', Action::class));

    $container // [!code ++]
        ->registerForAutoconfiguration('App\Action\ActionTypeInterface')  // [!code ++]
        ->addTag('action');  // [!code ++]
}
```

### Add and use type

Add a new type that implement our `ActionTypeInterface`.

```php
namespace App\Action\Type;

use App\Action\ActionTypeInterface;
use Enhavo\Component\Type\AbstractType;
use Doctrine\ORM\EntityManagerInterface;

class SaveActionType extends AbstractType implements ActionTypeInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function buildViewData(array $options, &$data): void
    {
        $data['label'] = $options['label'];
        $data['icon'] = $options['icon'];
    }

    public function execute(array $options, $resource): void
    {
        $this->em->persist($resource);
        $this->em->flush();
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'Save',
            'icon' => 'save',
        ]);
    }

    public static function getName(): ?string
    {
        return 'save';
    }
}
```

Now you can retrieve an action with a save type.

```php{10,17-22,31-35}
namespace App\Controller;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BookController
{
    public function __construct(
        private readonly FactoryInterface $actionFactory,
    )
    {
    }

    public function index(Request $request)
    {
        $action = $this->actionFactory->create([
            'type' => 'save',
            'icon' => 'disk',
        ])
        
        $data = $action->getViewData();
        
        return new JsonResponse($data);
    }
    
    public function save(Request $request)
    {
        $resource = $this->getResourceByRequest();
    
        $action = $this->actionFactory->create([
            'type' => 'save',
        ])
        
        $action->execute($resource);
        
        return new JsonResponse();
    }
    
    private function getResourceByRequest(Request $request) 
    {
        // ...
    }
}
```

### Abstract and base type

tbc.