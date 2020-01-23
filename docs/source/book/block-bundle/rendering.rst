Rendering
=========

.. note::

  This article outdated and may contain information that are not in use any more

To render the content in the frontend, you can use the twig function ``grid_render``. This function has
three parameters. The first one is the grid object.
The second one is optional and is used to render a different template set. For further information read the
section ``Render sets`` below.
The third parameter is optional as well and can be used to exclude grid types from rendering.

If you use ``grid_render`` with one parameter, it will render the default template for the item which is defined
in your configuration in app/config/enhavo.yml.

.. code-block:: twig

    grid_render(grid)

    {# render with specific set #}
    grid_render(grid, 'page')

    {# only render text fields #}
    grid_render(grid, null, ['text'])


Render sets
-----------

If you want to render two different templates for the same item type in your application, then you can use render sets.

In the configuration in app/config/enhavo.yml you can define render sets. In this example, we defined one called
``page``. Now we can define a new template for each item. Items without a defined template will use the default
template.

.. code-block:: yaml

    enhavo_grid:
        render:
            sets:
                page:
                    picture: ProjectBundle:Item:page/picture.html.twig
                    text: ProjectBundle:Item:page/text.html.twig
