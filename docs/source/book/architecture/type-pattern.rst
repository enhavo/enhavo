Type Pattern
============

The ``Type Pattern`` is a behavioral pattern which is widley used in enhavo. It is not an official pattern like you may know
from the Gang of Four. While developing on enhavo we realize that we need a abstract workflow
to convert configurations into direct php classes. So over the time we found this pattern
in our code a try to standardize it.

For programmers who use enhavo as a normal cms, it is not important to know how this pattern works in detail,
but it will get more interesting for you if you want to understand and work at the enhavo project.

The goal of this pattern is to work on objects that encapsulate the configuration they depends on and therefor
having different behaviour while using the same api.

For example, think of different actions or buttons in a system, that may configure in yaml like this:

.. code-block:: yaml

    create:
        type: create
    save:
        type: save
        route: my_save_route
    delete:
        type: delete
        label: My custom delete label
        icon: trash

The simplest configuration for a type is just the type option itself.
When create the type you can take care of the options with the Symfony ``OptionResolver``
Here you can also set default and required options.

Later on you want a object with the same api to handle your actions, but you are not interested
how this object is configured inside.

.. code-block:: php

    <?php

        $actions = $manager->getActions($configuration);
        $viewData = [];
        foreach($action as $actions) {
            $viewData[$action->getKey()] = $action->createViewData($resource);
        }

After you get the actions from the manager, you don't need the configuration anymore.
Also you don't want to handle with ``OptionResolver``, this logic should be encapsulate in the action.
How the view data is assembled is delegated to the type.

.. code-block:: php

    <?php
        class SaveActionType extends ActionTypeInterface
        {
            public function createViewData(array $options, $resource = null)
            {
                return [
                    'label' => $options['label'],
                    'icon' => $options['icon'],
                    'url' => $this->router->generate($options['route']);
                ];
            }

            public function configureOptions(OptionsResolver $resolver)
            {
                $resolver->setDefaults([
                    'label' => 'Save'
                    'icon' => 'disk',
                ])
                $resolver->setRequired('route');
            }
        }

Thanks to the symfony dependency injection container we can add this type by tagging to the central repository
and we will only created the type if it's really used.

.. note::

    The concrete type is also created once, so you are not allowed to store option or e.g. resource data
    to a member inside the type.


The following UML shows the classes involved by the ``Type Pattern``.

.. image:: /_static/image/type-pattern.png
