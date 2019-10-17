Create Bundle
-------------


In the configuration definition we can see, that we use the model, the form and a repository, we’ve already created. But what about the controller and where does it come from? Well, the directory tells us, that the Resource Controller is part of the Enhavo App Bundle and its name tells us, that it probably handles resources. Hopefully, you can remember the Resource Interface, we used to create our Product Entity. In Enhavo, all entities you want to create for your application, and which should be creatable, readable, editable and deletable by the user need this interface so the Resource Controller can work with them
If you want to provide a bundle to enhance enhavo for further projects use the ``Configuration.php`` in ``ProjectBundle/DependencyInjection`` like in the Example below:

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
