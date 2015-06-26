<?php
/**
 * EditViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace esperanto\AdminBundle\Viewer;


class EditViewer extends AbstractViewer
{
    public function getTabs()
    {
        return $this->getConfig()->get('tabs');
    }

    public function getButtons()
    {
        return $this->getConfig()->get('buttons');
    }

    public function getFormTemplate()
    {
        return $this->getConfig()->get('form.template');
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
        $action = $this->getConfig()->get('form.action');
        return $this->container->get('router')->generate($action, array(
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
            'resource' => $this->getResource(),
            'form_template' => $this->getFormTemplate(),
            'form_action' => $this->getFormAction(),
            'form_delete' => $this->getFormDelete()
        );

        $parameters = array_merge($this->getTemplateVars(), $parameters);

        return $parameters;
    }
}