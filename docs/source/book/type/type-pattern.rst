Type Pattern
============

The ``Type Pattern`` is a behavioral pattern which is widely used in enhavo. It is not an official pattern like the Gang of Four, which you probably know.
While developing enhavo, we realized that we need an abstract workflow to convert configurations directly into php classes.
So over time we found this pattern in our code and standardized it.

For developers who use enhavo as a normal CMS, it is not important to understand this pattern in detail,
but the more you work with enhavo, the more interesting it will be for you.

The Goal of this pattern is to create objects with an encapsulated configuration and different behaviours - but using the same api.

Think of different actions or buttons which can be configured easily in a yaml file like this:

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
When creating the type we handle the options with the Symfony ``OptionResolver``.
It allows you to set the possible options, and also the defaults and required options.

Later on you want a object with the same api to handle your actions, but you don't have to take care
about how this object is configured inside.

.. code-block:: php

    <?php

        $actions = $manager->getActions($configuration);
        $viewData = [];
        foreach($actions as $action) {
            $viewData[$action->getKey()] = $action->createViewData($resource);
        }

As a user of actions you don't want to deal with ``OptionResolver``, this logic should be encapsulated inside the action.
The assembly of the view data is delegated to the type. After you get the actions from the manager, you don't need the configuration anymore.

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

Thanks to the symfony dependency injection container we can add this type to the central repository by tagging,
and it will only be instantiated if it is really used.

.. note::

    The concrete type is also created once, so you are not allowed to store option or e.g. resource data
    to a member inside the type.


The following UML shows the classes involved in the ``Type Pattern``.

.. image:: /_static/image/type-pattern.png
