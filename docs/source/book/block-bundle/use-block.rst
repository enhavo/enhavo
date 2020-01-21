Using block
===========


Adding to the model
-------------------

To add a property of type ``grid`` to your resource model, add a one-to-one association to your doctrine definition
and update your entity.

.. code-block:: yaml

    oneToOne:
        grid:
            cascade: ['persist', 'refresh', 'remove']
            targetEntity: Enhavo\Bundle\GridBundle\Model\GridInterface

.. code-block:: php

    <?php

    //...

    class Foo {

    //...

    protected $grid;

    public function getGrid() {...}
    public function setGrid(...) {...}

    //...

    }


Adding to form
--------------

To properly edit a property of the type ``grid`` in your form, use the form type ``enhavo_grid``.

.. code-block:: php

    $builder->add('grid', 'enhavo_grid');


Types of grid
-------------

If you don't add any options, all item types configured in app/config/enhavo.yml will be available in the form. You
can restrict the available types by setting the option ``items``.

.. code-block:: php

    $builder->add('grid', 'enhavo_grid', array(
        'items' => array(
            array('type' => 'text'),
            array('type' => 'picture', 'label' => 'Picture'),
            array('type' => 'video', 'label' => 'label.video', 'translationDomain' => 'AcmeFooBundle')
        )
    ));

The parameter ``label`` is optional, its default value can be configured in app/config/enhavo.yml.
The parameter ``translationDomain`` defaults to "EnhavoGridBundle" if it is not set.
