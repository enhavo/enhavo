## Poly Collection

This type is used for collections, which need different form types for
each item. It will ask the user to select a type if he add a new item.

### Basic Usage

```php
use Enhavo\Bundle\FormBundle\Form\Type\PolyCollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
// ...

$builder->add('items', PolyCollectionType::class, [
    // a list of entry types, which the user can choose when pressing add button
    'entry_types' => [
        'text' => TextType::class,
        'date' => DateType::class,
    ],
    // these options are passed to type
    'entry_types_options' => [
        'text' => ['label' => 'I am a label'],
        'date' => ['label' => 'Type in the date'],
    ],
    // tell how we know what type the data is
    'entry_type_resolver' => function($data) {
        return $data instanceof DateType ? 'date' : 'text';
    },
]);
```

### Field Options

**entry_types**

**entry_types_options**

**entry_type_resolver**
