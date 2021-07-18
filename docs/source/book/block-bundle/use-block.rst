Using block
===========

Adding to the model
-------------------

To add a property of type ``node`` to your resource model, add a one-to-one association to your doctrine definition and update your entity. In this case, the property is called "content".

.. code-block:: yaml

    oneToOne:
        content:
            cascade: ['persist', 'refresh', 'remove']
            targetEntity: Enhavo\Bundle\BlockBundle\Model\NodeInterface

.. code-block:: php

    <?php

    //...

    class Foo {

    //...

    /**
     * @var NodeInterface
     */
    protected $content;

    public function getContent() {...}
    public function setContent(...) {...}

    //...

    }


Adding to form
--------------

To properly edit a property of the type ``node`` in your form, use the form type ``BlockNodeType::class`` or ``enhavo_block_block_node``.

.. code-block:: php

    $builder->add('content', BlockNodeType::class);


Limit node types for a FormType
-------------

If you don't add any options, all item types configured in config/packages/enhavo_block.yaml will be available in the form

items (array)
~~~~~

You can restrict the available types by setting the option ``items``.

.. code-block:: php

    $builder->add('content', BlockNodeType::class, array(
        'items' => ['text','my_block_node_name']
    ));

item_groups (array)
~~~~~~~~~~~

You can restrict the available types also by setting the option ``item_groups``. Beforehand you need to define these ``item_groups`` in each block node definition in config/packages/enhavo_block.yaml like so:

.. code-block:: yaml

    enhavo_block:
        blocks:
            my_block_node:
                groups: [ my_group, maybe_a_second_group ]
            my_second_block_node:
                groups: [ maybe_a_second_group ]


Afterwards you can use these groups in your FormType:

.. code-block:: php

    $builder->add('content', BlockNodeType::class, array(
        'item_groups' => ['my_group']
    ));

In this case, only "my_block_node" would be available, as "my_second_block_node" does not belong to that group.