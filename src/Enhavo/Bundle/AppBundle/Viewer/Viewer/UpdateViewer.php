<?php
/**
 * UpdateViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\OptionAccessor;
use FOS\RestBundle\View\View;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateViewer extends CreateViewer
{
    public function getActionRoute()
    {
        return sprintf(
            '%s_%s_update',
            $this->metadata->getApplicationName(),
            $this->getUnderscoreName()
        );
    }

    public function getFormDelete()
    {
        $route = $this->optionAccessor->get('form.delete');
        if($route == null) {
            return null;
        }

        $parameters = $this->optionAccessor->get('form.delete_parameters');
        if($parameters === null) {
            $parameters = [
                'id' => $this->resource->getId()
            ];
        }

        return $this->container->get('router')->generate($route, $parameters);
    }

    public function getFormAction()
    {
        $route = $this->optionAccessor->get('form.action');
        if($route == null) {
            return null;
        }

        $parameters = $this->optionAccessor->get('form.action_parameters');
        if($parameters === null) {
            $parameters = [
                'id' => $this->resource->getId()
            ];
        }
        return $this->container->get('router')->generate($route, $parameters);
    }

    public function getDeleteRoute()
    {
        return sprintf(
            '%s_%s_delete',
            $this->metadata->getApplicationName(),
            $this->getUnderscoreName()
        );
    }

    public function getType()
    {
        return 'update';
    }

    public function createView($options = []): View
    {
        if($this->isValidationError() && $this->configuration->isAjaxRequest()) {
            return parent::createView();
        }
        $view = parent::createView($options);
        $view->setTemplate($this->configuration->getTemplate('EnhavoAppBundle:Resource:update.html.twig'));
        $view->setTemplateData(array_merge($view->getTemplateData(), [
            'form_action' => $this->getFormAction(),
            'form_delete' => $this->getFormDelete(),
        ]));
        return $view;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
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
                'delete' => $this->getDeleteRoute(),
                'delete_parameters' => null
            )
        ]);
    }
}