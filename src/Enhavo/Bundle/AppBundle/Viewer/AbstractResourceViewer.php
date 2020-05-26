<?php
/**
 * AbstractResourceViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use Enhavo\Bundle\AppBundle\Type\AbstractType;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractResourceViewer extends AbstractType implements ViewerInterface
{
    use TemplateTrait;

    /**
     * @var RequestConfigurationFactory
     */
    private $requestConfigurationFactory;

    /**
     * @var ViewerUtil
     */
    protected $util;

    /**
     * AbstractResourceViewer constructor.
     *
     * @param RequestConfigurationFactory $requestConfigurationFactory
     * @param ViewerUtil $util
     */
    public function __construct(RequestConfigurationFactory $requestConfigurationFactory, ViewerUtil $util)
    {
        $this->requestConfigurationFactory = $requestConfigurationFactory;
        $this->util = $util;
    }

    /**
     * {@inheritdoc}
     */
    public function createView($options = []): View
    {
        $view = $this->create($options);
        $requestConfiguration = $this->getRequestConfiguration($options);

        // set template data
        $parameters = new ParameterBag();
        $this->buildTemplateParameters($parameters, $requestConfiguration, $options);
        $templateVars = [];
        foreach($parameters as $key => $value) {
            $templateVars[$key] = $value;
        }
        $view->setTemplateData($templateVars);

        // set template
        if(isset($options['template'])) {
            $view->setTemplate($this->getTemplate($this->resolveTemplate($requestConfiguration, $options)));
        }

        return $view;
    }

    protected function create($options): View
    {
        $view = null;
        if(isset($options['resource'])) {
            $view = View::create($options['resource'], 200);
        } elseif(isset($options['resources'])) {
            $view = View::create($options['resources'], 200);
        } else {
            $view = View::create(null, 200);
        }
        return $view;
    }

    protected function getRequestConfiguration(array $options): ?RequestConfiguration
    {
        $requestConfiguration = $options['request_configuration'];
        if($requestConfiguration instanceof RequestConfiguration) {
            return $requestConfiguration;
        } else {
            /** @var Request $request */
            $request = $options['request'];
            $metadata = new DummyMetadata();
            /** @var RequestConfiguration $requestConfiguration */
            $requestConfiguration = $this->requestConfigurationFactory->create($metadata, $request);
            return $requestConfiguration;
        }
    }

    private function resolveTemplate(RequestConfiguration $requestConfiguration, array $options)
    {
        return $requestConfiguration->getTemplate($options['template']);
    }

    protected function getUnderscoreName(MetadataInterface $metadata)
    {
        $name = $metadata->getHumanizedName();
        $name = str_replace(' ', '_', $name);
        return $name;
    }

    protected function buildTemplateParameters(ParameterBag $parameters, RequestConfiguration $requestConfiguration, array $options)
    {
        $parameters->set('resource', $options['resource']);
        $parameters->set('resources', $options['resources']);

        $parameters->set('translation_domain', $this->mergeConfig([
            $options['translation_domain'],
            $this->getViewerOption('translation_domain', $requestConfiguration)
        ]));
    }

    protected function mergeConfigArray($configs)
    {
        $data = [];
        foreach($configs as $config) {
            if(is_array($config)) {
                $data = array_merge($data, $config);
            }
        }
        return $data;
    }

    protected function mergeConfig($configs)
    {
        $data = null;
        foreach($configs as $config) {
            if($config != null) {
                $data = $config;
            }
        }
        return $data;
    }

    protected function getViewerOption($key, RequestConfiguration $requestConfiguration)
    {
        $options = $requestConfiguration->getViewerOptions();
        return $this->util->getConfigValue($key, $options);
    }

    protected function getViewId()
    {
        return $this->container->get('request_stack')->getMasterRequest()->get('view_id');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'resource' => null,
            'resources' => null,
            'metadata' => null,
            'template' => null,
            'request_configuration' => null,
            'request' => null,
            'label' => null,
            'translation_domain' => null
        ]);
    }
}
