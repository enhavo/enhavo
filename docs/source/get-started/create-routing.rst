Create Routing
==============
Add Configuration
-----------------

The next step is adding the new resource consisting of the Product entity and the associated ProductType form to the Enhavo configuration.
You can do this in two different ways. If you just want to use Enhavo to develop an application with a great CMS you should add this to the ``enhavo.yml`` in ``App/config/packages``

.. code-block:: yaml
    :linenos:

    sylius_resource:
        resources:
            app.product:
                classes:
                    model: App\Entity\Product
                    controller: Enhavo\Bundle\AppBundle\Controller\ResourceController
                    form: App\Form\Type\ProductType
                    repository: App\Repository\ProductRepository

In the configuration definition we can see, that we use the model, the form and a repository, we’ve already created. But what´s about the controller and where does it come from? Well, the directory tells us, that the Resource Controller is part of the Enhavo App Bundle and its name tells us, that it probably handles resources. Hopefully, you can remember the Resource Interface, we used to create our Product Entity. In Enhavo, all entities you want to create for your application, and which should be creatable, readable, editable and deletable by the user need this interface so the Resource Controller can work with them
If you want to provide a bundle to enhance enhavo for further projects use the ``Configuration.php`` in ``ProjectBundle/DependencyInjection` like in the Example below:

.. code-block:: php

    <?php
    // The resources
    $rootNode
        ->children()
            ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
        ->end()
        ->children()
            ->arrayNode('resources')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('project')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->variableNode('options')->end()
                            ->arrayNode('classes')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('model')->defaultValue('ProjectBundle\Entity\Product')->end()
                                    ->scalarNode('controller')->defaultValue('Enhavo\Bundle\AppBundle\Controller\ResourceController')->end()
                                    ->scalarNode('repository')->defaultValue('Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository')->end()
                                    ->scalarNode('factory')->defaultValue('Sylius\Component\Resource\Factory\Factory')->end()
                                    ->arrayNode('form')
                                        ->addDefaultsIfNotSet()
                                        ->children()
                                            ->scalarNode('default')->defaultValue('App\Form\Type\ProductType')->cannotBeEmpty()->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end()
    ;

.. note::
    If you use this second option, the file ``ProjectBundle/DependencyInjection/ProductExtension.php`` has to extend ``SyliusResourceExtension``, otherwise the services won’t work.

Generate new routes
-------------------

After we add our new resource to the configuration, we still have to connect the resource, form and actions (Create, View, Edit, Delete) with the still existing Resource Controller.
Normally, that would be much work, but Enhavo gives us a Command, which generates the basic routing after some simple questions.

    bin/console make:enhavo:routing

If you want your resource to be sortable, just type “yes” if the script asks you this question. The value of the parameter is a property type integer in your resource entity to save the items position. In this example it is called position.

This is the code we get:

.. code-block:: yaml
    :linenos:

    app_product_index:
        options:
            expose: true
        path: /app/product/index
        methods: [GET]
        defaults:
            _controller: app.controller.product:indexAction
            _sylius:
                viewer:

    app_product_create:
        options:
            expose: true
        path: /app/product/create
        methods: [GET,POST]
        defaults:
            _controller: app.controller.product:createAction
            _sylius:
                redirect: app_product_update
                viewer:

    app_product_update:
        options:
            expose: true
        path: /app/product/update/{id}
        methods: [GET,POST]
        defaults:
            _controller: app.controller.product:updateAction
            _sylius:
                viewer:

    app_product_table:
        options:
            expose: true
        path: /app/product/table
        methods: [GET,POST]
        defaults:
            _controller: app.controller.product:tableAction
            _sylius:
                viewer:
                    columns:
                        id:
                            property: id
                            width: 12
                            label: id
                            type: property

    app_product_delete:
        options:
            expose: true
        path: /app/product/delete/{id}
        methods: [POST]
        defaults:
            _controller: app.controller.product:deleteAction

    app_product_batch:
        options:
            expose: true
        path: /app/product/batch
        methods: [POST]
        defaults:
            _controller: app.controller.product:batchAction
            _sylius:
                paginate: false
                criteria:
                    id: $ids
                batches:
                    delete:
                        type: delete

    app_product_preview:
        options:
            expose: true
        path: /app/product/preview
        methods: [GET]
        defaults:
            _controller: app.controller.product:previewAction
            _sylius:
                viewer:

    app_product_resource_preview:
        options:
            expose: true
        path: /app/product/resource/preview
        methods: [POST]
        defaults:
            _controller: app.controller.product:previewResourceAction
            _sylius:
                viewer:
                    strategy_options:
                        service: enhavo_article.controller.article:showResourceAction

Create a new file called ``project.yml`` in ``App/routes/admin/product.yaml``. Copy the routes from the terminal into it.
After we have done this, we have to tell the ``routes.yaml`` in ``App/config/routes.yml`` where to find our new ``product.yaml``. That’s quite simple, we can just add

This routing file is very important for any customization affecting the resource view, handling, and processing.

.. code-block:: yaml
    :linenos:

    app_product:
        prefix: /admin
        resource: routes/admin/product.yaml

We have to dump all new routes with the following command, otherwise, they are not in our public application web folder and Enhavo won´t find them.

    yarn routes:dump


The last step is to add a new menu topic to the main menu in Enhavo. We can do this by adding this code to the enhavo.yml in App/config/packages .

.. code-block:: yaml
    :linenos:

    menu:
        project:
            label: Project
            route: app_product_index
            role: ROLE_ENHAVO_PROJECT_PROJECT_INDEX
            icon: box
