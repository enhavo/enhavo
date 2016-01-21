<?php
/**
 * CreateViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;


class CreateViewer extends AbstractViewer
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
                )
            ),
            'form' => array(
                'template' => 'EnhavoAppBundle:View:tab.html.twig',
                'theme' => '',
                'action' => sprintf('%s_%s_create', $this->getBundlePrefix(), $this->getResourceName())
            )
        );
    }

    public function getTabs()
    {
        $tabs = $this->getConfig()->get('tabs');
        if(empty($tabs)) {
            return array(
                $this->getResourceName() => array(
                    'label' => $this->getResourceName().'.label.'.$this->getResourceName(),
                    'template' => 'EnhavoAppBundle:Tab:default.html.twig'
                )
            );
        }
        return $tabs;
    }

    public function getButtons()
    {
        $defaults = array(
            'route' => null,
            'display' => true,
            'role' => null,
            'label' => 'label.button',
            'icon' => null,
            'translationDomain' => 'EnhavoAppBundle'
        );

        $buttons = $this->getConfig()->get('buttons');

        foreach($buttons as &$button) {
            foreach($defaults as $key => $value)
            if(!array_key_exists($key, $button)) {
                $button[$key] = $value;
            }
        }

        return $buttons;
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
            'form_action' => $this->getFormAction(),
            'translationDomain' => $this->getTranslationDomain()
        );

        $parameters = array_merge($this->getTemplateVars(), $parameters);

        return $parameters;
    }
}