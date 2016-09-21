Adding a sylius extension
=========================

Edit the extension in the folder DependencyInjection of your bundle to be derived from ``SyliusResourceExtension``
and add the required parameters.

.. code-block:: php

    <?php

    namespace Acme\ProjectBundle\DependencyInjection;

    use Symfony\Component\DependencyInjection\ContainerBuilder;
    use Symfony\Component\Config\FileLocator;
    use Symfony\Component\DependencyInjection\Loader;
    use Enhavo\Bundle\AppBundle\DependencyInjection\SyliusResourceExtension;

    /**
     * This is the class that loads and manages your bundle configuration
     *
     * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
     */
    class AcmeProjectExtension extends AbstractResourceExtension
    {
        // You can choose your application name, it will be used as prefix for the configuration keys
        // in the container (the default value is sylius).
        protected $applicationName = 'acme_project';

        protected $bundleName = 'project';

        protected $companyName = 'acme';

        // You can define where your service definitions are located
        protected $configDirectory = '/../Resources/config';

        // You can define what service definitions you want to load
        protected $configFiles = array(
            'services',
            'forms',
        );

        // You can define the file format of the files loaded (default: xml)
        protected $configFormat = self::CONFIG_YAML;

        public function load(array $config, ContainerBuilder $container)
        {
            $this->configure(
                $config,
                new Configuration(),
                $container,
                self::CONFIGURE_LOADER | self::CONFIGURE_DATABASE | self::CONFIGURE_PARAMETERS | self::CONFIGURE_ADMIN
            );

            $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('services.yml');
        }
    }

After that you have to add the ``driver`` to your ``Configuration.php``

.. code-block:: php

    // Driver used by the resource bundle
    ->children()
        ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
    ->end()

Finally add the ``getSupportedDrivers`` function to your bundle.

.. code-block:: php

    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
