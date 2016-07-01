<?php
/**
 * UpdateViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\OptionAccessor;

class UpdateViewer extends CreateViewer
{
    public function getFormDelete()
    {
        $route = $this->optionAccessor->get('form.delete');
        return $this->container->get('router')->generate($route, array(
            'id' => $this->resource->getId()
        ));
    }

    public function getFormAction()
    {
        $route = $this->optionAccessor->get('form.action');
        return $this->container->get('router')->generate($route, [
            'id' => $this->resource->getId()
        ]);
    }

    public function getDeleteRoute()
    {
        return sprintf(
            '%s_%s_delete',
            $this->metadata->getApplicationName(),
            $this->metadata->getHumanizedName()
        );
    }

    public function getType()
    {
        return 'update';
    }

    public function createView()
    {
        $view = parent::createView();
        $view->setTemplate($this->configuration->getTemplate('EnhavoAppBundle:Resource:update.html.twig'));
        $view->setTemplateData(array_merge($view->getTemplateData(), [
            'form_action' => $this->getFormAction(),
            'form_delete' => $this->getFormDelete(),
        ]));
        return $view;
    }

    public function configureOptions(OptionAccessor $optionsAccessor)
    {
        parent::configureOptions($optionsAccessor);
        $optionsAccessor->setDefaults([
            'buttons' => [
                'cancel' => [
                    'type' => 'cancel',
                ],
                'save' => [
                    'type' => 'save',
                ],
                'delete' => [
                    'type' => 'delete'
                ],
            ],
            'form' => array(
                'template' => 'EnhavoAppBundle:View:tab.html.twig',
                'action' => $this->getActionRoute(),
                'delete' => $this->getDeleteRoute()
            )
        ]);
    }
}