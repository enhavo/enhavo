<?php
/**
 * AbstractViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Type\AbstractType;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractViewer extends AbstractType implements ViewerInterface
{
    use ContainerAwareTrait;

    /**
     * @var RequestConfigurationFactory
     */
    private $requestConfigurationFactory;

    /**
     * AbstractViewer constructor.
     *
     * @param RequestConfigurationFactory $requestConfigurationFactory
     */
    public function __construct(RequestConfigurationFactory $requestConfigurationFactory)
    {
        $this->requestConfigurationFactory = $requestConfigurationFactory;
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
            $view->setTemplate($this->resolveTemplate($requestConfiguration, $options));
        }

        return $view;
    }

    private function create($options): View
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

    private function getRequestConfiguration(array $options): RequestConfiguration
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

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'translation_domain' => null,
            'resource' => null,
            'metadata' => null,
            'template' => null,
            'request_configuration' => null,
            'request' => null,
        ]);
    }

    protected function getUnderscoreName(MetadataInterface $metadata)
    {
        $name = $metadata->getHumanizedName();
        $name = str_replace(' ', '_', $name);
        return $name;
    }

    protected function buildTemplateParameters(ParameterBag $parameters, RequestConfiguration $requestConfiguration, array $options)
    {

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
        if(isset($options[$key])) {
            return $options[$key];
        }
        return null;
    }
}