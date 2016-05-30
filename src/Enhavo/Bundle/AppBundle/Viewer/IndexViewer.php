<?php
/**
 * IndexViewer.php
 *
 * @since 26/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\Security\Roles\RoleUtil;

class IndexViewer extends AppViewer
{
    public function getDefaultConfig()
    {
        return array(
            'blocks' => array(
                'table' => array(
                    'type' => 'table',
                    'table_route' => sprintf('%s_%s_table', $this->getBundlePrefix(), $this->getResourceName()),
                    'update_route' => sprintf('%s_%s_update', $this->getBundlePrefix(), $this->getResourceName()),

                )
            ),
            'actions' => array(
                'create' => array(
                    'type' => 'create',
                    'route' => sprintf('%s_%s_create', $this->getBundlePrefix(), $this->getResourceName()),
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
        foreach($configActions as $action => $value) {
            $roleUtil = new RoleUtil();
            $roleName = $roleUtil->getRoleNameByResourceName($this->getBundlePrefix(), $this->getResourceName(), $action);
            if($securityContext->isGranted($roleName)){
                $actions[] = $value;
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