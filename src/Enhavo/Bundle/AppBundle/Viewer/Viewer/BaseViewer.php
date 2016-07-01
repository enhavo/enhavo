<?php
/**
 * AppViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\OptionAccessor;
use Enhavo\Bundle\AppBundle\Viewer\AbstractViewer;

class BaseViewer extends AbstractViewer
{
    public function getType()
    {
        return 'base';
    }

    /**
     * @return string[]
     */
    public function getStylesheets()
    {
        $stylesheets = $this->container->getParameter('enhavo_app.stylesheets');
        return array_merge($stylesheets, $this->optionAccessor->get('stylesheets'));
    }

    /**
     * @return string[]
     */
    public function getJavascripts()
    {
        $javascripts = $this->container->getParameter('enhavo_app.javascripts');
        return array_merge($javascripts, $this->optionAccessor->get('javascripts'));
    }

    public function createView()
    {
        $view = parent::createView();
        $view->setTemplate($this->configuration->getTemplate('EnhavoAppBundle:Viewer:base.html.twig'));
        $view->setTemplateData(array_merge($view->getTemplateData(), [
            'javascripts' => $this->getJavascripts(),
            'stylesheets' => $this->getStylesheets(),
        ]));
        return $view;
    }

    public function configureOptions(OptionAccessor $optionsAccessor)
    {
        parent::configureOptions($optionsAccessor);
        $optionsAccessor->setDefaults([
            'javascripts' => [],
            'stylesheets' => []
        ]);
    }
}