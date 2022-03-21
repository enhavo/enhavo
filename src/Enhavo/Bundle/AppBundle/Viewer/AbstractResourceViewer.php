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
use Enhavo\Bundle\AppBundle\Util\ArrayUtil;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractResourceViewer extends AbstractType implements ViewerInterface
{
    use TemplateTrait;

    private function resolveTemplate(RequestConfiguration $requestConfiguration, array $options)
    {
        return $requestConfiguration->getTemplate($options['template']);
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
