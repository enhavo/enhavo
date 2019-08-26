<?php
/**
 * AppViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Viewer\AbstractResourceViewer;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseViewer extends AbstractResourceViewer
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

        $parameters->set('translation_domain', $this->mergeConfig([
            $options['translation_domain'],
            $this->getViewerOption('translation_domain', $requestConfiguration)
        ]));

        $parameters->set('routes', $this->mergeConfig([
            $options['translation_domain'],
            $this->getViewerOption('translation_domain', $requestConfiguration)
        ]));

        $dumper = $this->container->get('enhavo_app.translation.translation_dumper');
        $translations = $dumper->getTranslations('javascript', $this->container->get('enhavo_app.locale_resolver')->resolve());
        $parameters->set('translations', $translations);
        $parameters->set('routes', $this->getRoutes());

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
            'translation_domain' => null,
            'template' => 'admin/view/base.html.twig',
            'parameters' => []
        ]);
    }

    private function getRoutes()
    {
        $file = $this->container->getParameter('kernel.project_dir').'/public/js/fos_js_routes.json';
        if(file_exists($file)) {
            return file_get_contents($file);
        }
        return null;
    }
}
