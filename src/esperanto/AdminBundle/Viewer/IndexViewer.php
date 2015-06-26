<?php
/**
 * IndexViewer.php
 *
 * @since 26/06/15
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
}