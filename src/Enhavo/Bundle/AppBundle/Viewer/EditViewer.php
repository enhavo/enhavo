<?php
/**
 * EditViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;


class EditViewer extends CreateViewer
{
    public function getDefaultConfig()
    {
        $config =  array(
            'buttons' => array(
                'cancel' => array(
                    'type' => 'cancel',
                ),
                'save' => array(
                    'type' => 'save',
                ),
            ),
            'form' => array(
                'template' => 'EnhavoAppBundle:View:tab.html.twig',
                'theme' => '',
                'action' => sprintf('%s_%s_update', $this->getBundlePrefix(), $this->getResourceName()),
                'delete' => sprintf('%s_%s_delete', $this->getBundlePrefix(), $this->getResourceName())
            )
        );

        $securityContext = $this->getContainer()->get('security.context');
        $route = sprintf('%s_%s_delete', $this->getBundlePrefix(), $this->getResourceName());
        if($securityContext->isGranted('ROLE_'.strtoupper($route))) {
            $config['buttons']['delete'] = array(
                'type' => 'delete',
            );
        }

        return $config;
    }

    public function getFormDelete()
    {
        $route = $this->getConfig()->get('form.delete');
        $securityContext = $this->container->get('security.context');
        if($securityContext->isGranted('ROLE_'.strtoupper($route))) {
            return $this->container->get('router')->generate($route, array(
                'id' => $this->getResource()->getId()
            ));
        }
        return null;
    }

    public function getFormAction()
    {
        $route = $this->getConfig()->get('form.action');
        return $this->container->get('router')->generate($route, array(
            'id' => $this->getResource()->getId()
        ));
    }

    public function getParameters()
    {
        $parameters = array(
            'buttons' => $this->getButtons(),
            'form' => $this->getForm(),
            'viewer' => $this,
            'tabs' => $this->getTabs(),
            'data' => $this->getResource(),
            'form_template' => $this->getFormTemplate(),
            'form_action' => $this->getFormAction(),
            'form_delete' => $this->getFormDelete(),
            'translationDomain' => $this->getTranslationDomain()
        );

        $parameters = array_merge($this->getTemplateVars(), $parameters);

        return $parameters;
    }
}