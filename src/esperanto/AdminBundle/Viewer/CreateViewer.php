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

    public function getCreateRoute()
    {
        return $this->getConfig()->get('create_route');
    }

    public function getPreviewRoute()
    {
        return $this->getConfig()->get('preview_route');
    }

    public function getFormTemplate()
    {
        return $this->getConfig()->get('form_template');
    }

    public function getParameters()
    {
        $parameters = array(
            'create_route' => $this->getCreateRoute(),
            'preview_route' => $this->getPreviewRoute(),
            'form_template' => $this->getFormTemplate(),
            'form' => $this->getForm(),
            'viewer' => $this,
            'tabs' => $this->getTabs()
        );

        return $parameters;
    }
}