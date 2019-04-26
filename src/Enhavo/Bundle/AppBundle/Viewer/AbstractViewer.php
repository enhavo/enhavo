<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-04-11
 * Time: 21:06
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

use FOS\RestBundle\View\View;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractViewer implements ViewerInterface
{
    use ContainerAwareTrait;

    abstract public function getType();

    /**
     * {@inheritdoc}
     */
    public function createView($options = []): View
    {
        $view = View::create(null, 200);
        $templateVars = [];

        $templateVars['stylesheets'] = $options['stylesheets'];
        $templateVars['javascripts'] = $options['javascripts'];

        if($options['translations']) {
            $templateVars['translations'] = $this->getTranslations();
        }

        if($options['routes']) {
            $templateVars['routes'] = $this->getRoutes();
        }

        $templateVars['data'] = [
            'view' => ['view_id' => null]
        ];

        $view->setTemplateData($templateVars);
        $view->setTemplate($options['template']);

        return $view;
    }

    private function getRoutes()
    {
        $file = $this->container->getParameter('kernel.project_dir').'/public/js/fos_js_routes.json';
        if(file_exists($file)) {
            return file_get_contents($file);
        }
        return null;
    }

    private function getTranslations()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $dumper = $this->container->get('enhavo_app.translation.translation_dumper');
        $translations = $dumper->getTranslations('javascript', $request->getLocale());
        return $translations;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'javascripts' => [],
            'stylesheets' => [],
            'translations' => false,
            'routes' => false,
            'template' => 'EnhavoAppBundle:Viewer:base.html.twig'
        ]);
    }
}
