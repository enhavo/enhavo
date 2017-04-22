Add new resource
================

You can easily add a new resource to the CMS by following these steps:

1) Create an Entity
2) Create a FormType
3) Add configuration
4) Generate new routes
5) Add resource to menu

As an example, we will be adding a resource called project.

Create an Entity
----------------

If you don't have an entity yet, you need to create one. This can be simply done over doctrine with following command.
The doctrine generator will also ask you to add some member vars. In our case we just add a title and a text as string.
To confirm just skip entering a new member var by double enter.

.. code-block:: bash

    app/console doctrine:generate:entity

Make sure you update the database after you finished creating your entity.

.. code-block:: bash

    app/console doctrine:schema:update --force

One more step is, to let the new entity implement the ``ResourceInterface`` from sylius.

.. code-block:: php

    <?php

    namespace ProjectBundle\Entity;

    use Sylius\Component\Resource\Model\ResourceInterface;

    class Project implements ResourceInterface
    {
        //...
    }



Create a FormType
-----------------

For the FormType add a new class called ``ProjectType`` to ``ProjectBundle/Form/Type``.

.. code-block:: php

    <?php

    namespace ProjectBundle\Form\Type;

    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Form\AbstractType;
    use Project\Entity\Project;

    class ProjectType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            //Here you add the fields you have just added to the entity
            //In our case for example 'title' and 'text'
            $builder->add('title', 'text', array(
                'label' => 'label.title'
            ));
            $builder->add('text', 'enhavo_wysiwyg', array(
                'label' => 'label.text'
            ));
        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => Project::class
            ));
        }

        public function getName()
        {
            return 'project_project';
        }
    }

To use the form you have to add the service in the ``service.yml`` on your own.

.. code-block:: yml

    services:
        project.form.project:
            class: ProjectBundle\Form\Type\ProjectType
            tags:
                - { name: form.type, alias: 'project_project' }


Add configuration
-----------------

Now you need to add the new resource to the configuration.
You can do this in two different ways.

Either you can do it in the ``config.yml`` in ``app/config``:

.. code-block:: yml

    sylius_resource:
        resources:
            project.project:
                classes:
                    model: ProjectBundle\Entity\Project
                    controller: Enhavo\Bundle\AppBundle\Controller\ResourceController
                    form:
                        default: ProjectBundle\Form\Type\ProjectType

or you add the resource to the ``Configuration.php`` in ``ProjectBundle/DependencyInjection``:

.. note::

    Use the Configuration over the ``Configuration.php`` only if you want to provide a bundle to enhance enhavo for further projects.

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
                                    ->scalarNode('model')->defaultValue('ProjectBundle\Entity\Project')->end()
                                    ->scalarNode('controller')->defaultValue('Enhavo\Bundle\AppBundle\Controller\ResourceController')->end()
                                    ->scalarNode('repository')->defaultValue('Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository')->end()
                                    ->scalarNode('factory')->defaultValue('Sylius\Component\Resource\Factory\Factory')->end()
                                    ->arrayNode('form')
                                        ->addDefaultsIfNotSet()
                                        ->children()
                                            ->scalarNode('default')->defaultValue('ProjectBundle\Form\Type\ProjectType')->cannotBeEmpty()->end()
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

If you use the second option, the file ``ProjectBundle/DependencyInjection/ProjectExtenstion.php`` has to extend
``SyliusResourceExtension``, otherwise the services won't work.

Generate new routes
-------------------

Now generate all the routes you need for the new resource.

.. code-block:: bash

    app/console enhavo:generate:routing project project

If you want your resource to be sortable, you can use the optional parameter "sorting" to additionally
generate sorting behaviour. The value of the parameter is a property type integer in your resource entity to save the
items position. In this example it is called ``position``.

.. code-block:: bash

    app/console enhavo:generate:routing project project --sorting="position"

Create a new file called ``project.yml`` in ``ProjectBundle/Resources/config/routing``.
Copy the routes from the terminal into it.

After you have done this, you have to tell the ``routing.yml`` in ``app/config`` where to find your new ``project.yml``

.. code-block:: yml

    project_project:
        resource: "@ProjectBundle/Resources/config/routing/project.yml"
        prefix:   /


Add resource to menu
--------------------

First we add the new resource to the menu in ``app/config/enhavo.yml``

.. code-block:: yml

    menu:
        project:
            label: Project
            route: project_project_index
            role: ROLE_ENHAVO_PROJECT_PROJECT_INDEX
            icon: box