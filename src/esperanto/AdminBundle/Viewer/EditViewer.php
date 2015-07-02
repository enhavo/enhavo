<?php
/**
 * EditViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace esperanto\AdminBundle\Viewer;


class EditViewer extends CreateViewer
{
    public function getDefaultConfig()
    {
        return array(
            'buttons' => array(
                'cancel' => array(
                    'route' => null,
                    'display' => true,
                    'role' => null,
                    'label' => 'label.cancel',
                    'icon' => 'close'
                ),
                'save' => array(
                    'route' => null,
                    'display' => true,
                    'role' => null,
                    'label' => 'label.save',
                    'icon' => 'check'
                ),
                'delete' => array(
                    'route' => null,
                    'display' => true,
                    'role' => null,
                    'label' => 'label.delete',
                    'icon' => 'trash'
                )

            ),
            'form' => array(
                'template' => 'esperantoAdminBundle:View:tab.html.twig',
                'theme' => '',
                'action' => sprintf('%s_%s_create', $this->getBundlePrefix(), $this->getResourceName()),
                'delete' => sprintf('%s_%s_delete', $this->getBundlePrefix(), $this->getResourceName())
            )
        );
    }

    public function getFormDelete()
    {
        $route = $this->getConfig()->get('form.delete');
        return $this->container->get('router')->generate($route, array(
            'id' => $this->getResource()->getId()
        ));
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
            'form_delete' => $this->getFormDelete()
        );

        $parameters = array_merge($this->getTemplateVars(), $parameters);

        return $parameters;
    }
}