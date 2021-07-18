Rendering
=========

To render the content in the frontend, you can use the twig function ``block_render``. This function has three parameters. The first one is the block node object.
The second one is optional and is used to render a different template set. For further information read the
section ``Render sets`` below.
The third parameter is optional as well and can be used to exclude block types from rendering.

If you use ``block_render`` with one parameter, it will render the default template for the item which is defined
in your configuration in config/packages/enhavo_block.yaml.

.. code-block:: twig

    block_render(node)

    {# render with specific set #}
    block_render(node, 'my_render_set_name')

    {# only render text fields #}
    block_render(node, null, ['text'])


Render sets
-----------

If you want to render two different templates for the same item type in your application, then you can use render sets.

In the configuration in config/packages/enhavo_block.yml you can define render sets. In this example, we defined one called
``my_render_set_name``. Now we can define a new template for each item. Items without a defined template will use the default template.

.. code-block:: yaml

    enhavo_grid:
        render:
            sets:
                my_render_set_name:
                    picture: theme/block/different-path/picture.html.twig
                    text: theme/block/different-path/text.html.twig
