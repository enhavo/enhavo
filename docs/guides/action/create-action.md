## Create Action

### Create Action Type


First you have to create your php action type class, which implements the ``Enhavo\Bundle\AppBundle\Action\ActionTypeInterface``. 
Enhavo provide some abstract classes with base functionality such as ``Enhavo\Bundle\AppBundle\Action\AbstractActionType`` and ``Enhavo\Bundle\AppBundle\Action\AbstractActionUrlType``.


```php
<?php

namespace App\Action;

class ExportActionType extends AbstractActionType implements ActionTypeInterface
{
    // the view data will be pass to directly to the component
    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);

        $data = array_merge($data, [

        ]);

        return $data;
    }

    // define options for your action
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'download-action',
            'label' => 'Export',
            'icon' => 'download',
        ]);
    }

    public function getType()
    {
        return 'export';
    }
}
```


### Add to service

Now you have to add the created class to the dependency injection container.

```yaml
App\Action\ExportActionType:
    public: true
    tags:
        - { name: 'enhavo.action', alias: 'export' }
```



