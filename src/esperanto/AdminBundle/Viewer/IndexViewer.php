<?php
/**
 * AppViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace esperanto\AdminBundle\Viewer;


class IndexViewer extends AbstractViewer
{
    public function getBlocks()
    {
        return $this->getConfig()->get('blocks');
    }

    public function getActions()
    {
        return $this->getConfig()->get('actions');
    }

    public function getParameters()
    {
        $parameters = array(
            'viewer' => $this,
            'blocks' => $this->getBlocks(),
            'actions' => $this->getActions()
        );

        $parameters = array_merge($this->getTemplateVars(), $parameters);

        return $parameters;
    }

    public function getProperty($resource, $property)
    {
        $method = sprintf('get%s', ucfirst($property));
        $value = call_user_func(array($resource, $method));
        return $value;
    }
}