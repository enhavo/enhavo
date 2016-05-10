<?php
/**
 * IndexViewer.php
 *
 * @since 26/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

class IndexViewer extends AppViewer
{
    public function getDefaultConfig()
    {
        return array(
            'blocks' => array(
                'table' => array(
                    'type' => sprintf('%s_%s_table', $this->getBundlePrefix(), $this->getResourceName()),
                    'parameters' => array(
                        'table_route' => sprintf('%s_%s_table', $this->getBundlePrefix(), $this->getResourceName()),
                        'update_route' => sprintf('%s_%s_update', $this->getBundlePrefix(), $this->getResourceName()),
                    )
                )
            ),
            'actions' => array(
                'create' => array(
                    'type' => 'overlay',
                    'route' => sprintf('%s_%s_create', $this->getBundlePrefix(), $this->getResourceName()),
                    'icon' => 'plus',
                    'label' => 'label.create'
                )
            )
        );
    }

    public function getBlocks()
    {
        return $this->getConfig()->get('blocks');
    }

    public function getActions()
    {
        $actions = [];
        $securityContext = $this->container->get('security.context');
        $configActions = $this->getConfig()->get('actions');
        if(!is_array($configActions)) {
            return [];
        }
        foreach($this->getConfig()->get('actions') as $action) {
            $currentRole = 'ROLE_'.strtoupper($action['route']);
            $resource = array();
            $resource['entity'] = $this->getResourceName();
            //ToDo: move Workflow check to hook
            if($securityContext->isGranted($currentRole) && $securityContext->isGranted('WORKFLOW', $resource)){
                $actions[] = $action;
            }
        }
        return $actions;
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