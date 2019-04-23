<?php
/**
 * AppViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppViewer extends BaseViewer
{
    public function getType()
    {
        return 'app';
    }

    protected function buildTemplateParameters(ParameterBag $parameters, RequestConfiguration $requestConfiguration, array $options)
    {
        parent::buildTemplateParameters($parameters, $requestConfiguration, $options);

        $parameters->set('actions', $this->mergeConfigArray([
            $options['actions'],
            $this->getViewerOption('actions', $requestConfiguration)
        ]));
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'actions' => []
        ]);
    }
}