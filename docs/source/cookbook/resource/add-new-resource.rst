Add new resource
================

You can easily add a new resource to the CMS by following these steps:

1) Create an Entity and FormType
2) Add resource to menu
3) Add configuration
4) Generate new routes


As an example, we will be adding a resource called project.


Create an Entity and FormType
-----------------------------

Finally you can create the Entity and FormType.

Create the Entity with the following command.

.. code-block:: bash

    app/console doctrine:generate:entity

After that you have to update your database.

.. code-block:: bash

    app/console doctrine:schema:update --force

For the FormType add a new file called ``ProjectType.php`` to ``ProjectBundle/Form/Type``.

.. code-block:: php

    <?php

    namespace Acme\ProjectBundle\Form\Type;

    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Form\AbstractType;

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
                'data_class' => 'Acme\ProjectBundle\Entity\Project'
            ));
        }

        public function getName()
        {
            return 'acme_project_project';
        }
    }

To use the form you have to add the service in the ``service.yml`` on your own.

.. code-block:: yml

    services:
        acme_project_project:
            class: %acme_project.form.type.project.class%
            tags:
                - { name: form.type }



Add resource to menu
--------------------

First we add the new resource to the menu in ``app/config/enhavo.yml``

.. code-block:: yml

    menu:
        project:
            label: label.project
            translationDomain: ProjectBundle
            route: acme_project_project_index
            role: ROLE_ENHAVO_ACME_PROJECT_PROJECT_INDEX

Add configuration
-----------------

Now you need to add the new resource to the configuration.
You can do this in two different ways.

Either you can do it in the ``config.yml`` in ``app/config``:

.. code-block:: yml

    sylius_resource:
        resources:
            acme_project.project:
                classes:
                    model: Acme\ProjectBundle\Entity\Project
                    controller: Acme\ProjectBundle\Controller\ProjectController
                    form:
                        default: Acme\ProjectBundle\Form\Type\ProjectType

or you add the resource to the ``Configuration.php`` in ``ProjectBundle/DependencyInjection``:

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
                    ->arrayNode('user')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->variableNode('options')->end()
                            ->arrayNode('classes')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('model')->defaultValue('Acme\ProjectBundle\Entity\Project')->end()
                                    ->scalarNode('controller')->defaultValue('Enhavo\Bundle\AppBundle\Controller\ResourceController')->end()
                                    ->scalarNode('repository')->defaultValue('Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository')->end()
                                    ->scalarNode('factory')->defaultValue('Sylius\Component\Resource\Factory\Factory')->end()
                                    ->arrayNode('form')
                                        ->addDefaultsIfNotSet()
                                        ->children()
                                            ->scalarNode('default')->defaultValue('Acme\ProjectBundle\Form\Type\ProjectType')->cannotBeEmpty()->end()
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

If you use the second option, the file ``ProjectBundle/DependencyInjection/AcmeProjectExtenstion.php`` has to extend
``SyliusResourceExtension``, otherwise the services won't work.

Generate new routes
-------------------

Now generate all the routes you need for the new resource.

.. code-block:: bash

    app/console enhavo:generate:routing acme_project project

If you want your resource to be sortable by the user, you can use the optional parameter "sorting" to additionally
generate sorting behaviour. The value of the parameter is a property type integer in your resource entity to save the
items position. In this example it is called ``position``.

.. code-block:: bash

    app/console enhavo:generate:routing acme_project project --sorting="position"

Create a new file called ``project.yml`` in ``ProjectBundle/Resources/config/routing``.
Copy the routes from the terminal into it.

After you have done this, you have to tell the ``routing.yml`` in ``app/config`` where to find your new ``project.yml``

.. code-block:: yml

    acme_project_project:
        resource: "@AcmeProjectBundle/Resources/config/routing/project.yml"
        prefix:   /