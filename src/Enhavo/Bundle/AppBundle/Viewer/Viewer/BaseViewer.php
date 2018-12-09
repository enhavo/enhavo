<?php
/**
 * AppViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Viewer\AbstractViewer;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseViewer extends AbstractViewer
{
    public function getType()
    {
        return 'base';
    }

    protected function buildTemplateParameters(ParameterBag $parameters, RequestConfiguration $requestConfiguration, array $options)
    {
        parent::buildTemplateParameters($parameters, $requestConfiguration, $options);

        $parameters->set('stylesheets', $this->mergeConfigArray([
            $this->container->getParameter('enhavo_app.stylesheets'),
            $options['stylesheets'],
            $this->getViewerOption('stylesheets', $requestConfiguration)
        ]));

        $parameters->set('javascripts', $this->mergeConfigArray([
            $this->container->getParameter('enhavo_app.javascripts'),
            $options['javascripts'],
            $this->getViewerOption('stylesheets', $requestConfiguration)
        ]));

        $parameters->set('requireJsApps', $this->mergeConfigArray([
            $this->container->getParameter('enhavo_app.apps'),
            $options['apps'],
            $this->getViewerOption('apps', $requestConfiguration)
        ]));

        $parameters->set('translationDomain', $this->mergeConfig([
            $options['translation_domain'],
            $this->getViewerOption('translationDomain', $requestConfiguration)
        ]));

        foreach($options['parameters'] as $key => $value) {
            $parameters->set($key, $value);
        }
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'javascripts' => [],
            'stylesheets' => [],
            'apps' => [],
            'translation_domain' => null,
            'template' => 'EnhavoAppBundle:Viewer:base.html.twig',
            'parameters' => []
        ]);
    }
}