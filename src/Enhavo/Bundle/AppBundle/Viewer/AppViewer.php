<?php
/**
 * AppViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;


class AppViewer extends AbstractViewer
{

    public function getBlocks()
    {
        $blocks = $this->getConfig()->get('blocks');
        if(!is_array($blocks)) {
            $blocks = [];
        }
        foreach($blocks as &$block) {
            if(!array_key_exists('parameters', $block)) {
                $block['parameters'] = null;
            }
        }
        return $blocks;
    }

    public function getActions()
    {
        return $this->getConfig()->get('actions');
    }

    public function getTitle()
    {
        return $this->getConfig()->get('title');
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