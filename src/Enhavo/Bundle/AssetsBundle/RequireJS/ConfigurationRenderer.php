<?php
/**
 * ConfigurationRenderer.php
 *
 * @since 15/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AssetsBundle\RequireJS;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ConfigurationRenderer extends \Twig_Extension
{
    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(Configuration $configuration, ContainerInterface $container)
    {
        $this->configuration = $configuration;
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('require_js_configuration', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render()
    {
        $config = $this->configuration->getConfiguration();

        return $this->container->get('templating')->render('EnhavoAssetsBundle::require_js.html.twig', [
            'configuration' => json_encode($config)
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'require_js_configuration';
    }
}