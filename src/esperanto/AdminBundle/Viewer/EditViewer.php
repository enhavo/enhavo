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

    public function getPreviewRoute()
    {
        return $this->getConfig()->get('preview_route');
    }

    public function getUpdateRoute()
    {
        return $this->getConfig()->get('update_route');
    }

    public function getDeleteRoute()
    {
        return $this->getConfig()->get('delete_route');
    }

    public function getFormTemplate()
    {
        return $this->getConfig()->get('form_template');
    }

    public function getParameters()
    {
        $parameters = array(
            'preview_route' => $this->getPreviewRoute(),
            'update_route' => $this->getUpdateRoute(),
            'delete_route' => $this->getDeleteRoute(),
            'form_template' => $this->getFormTemplate(),
            'form' => $this->getForm(),
            'viewer' => $this,
            'tabs' => $this->getTabs(),
            'data' => $this->getResource()
        );

        return $parameters;
    }
}