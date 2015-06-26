<?php
/**
 * CreateViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace esperanto\AdminBundle\Viewer;


class CreateViewer extends AbstractViewer
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

    public function getFormAction()
    {
        $action = $this->getConfig()->get('form.action');
        return $this->container->get('router')->generate($action);
    }

    public function getParameters()
    {
        $parameters = array(
            'buttons' => $this->getButtons(),
            'form' => $this->getForm(),
            'viewer' => $this,
            'tabs' => $this->getTabs(),
            'form_template' => $this->getFormTemplate(),
            'form_action' => $this->getFormAction()
        );

        $parameters = array_merge($this->getTemplateVars(), $parameters);

        return $parameters;
    }
}