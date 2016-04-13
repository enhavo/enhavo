<?php
/**
 * IndexViewer.php
 *
 * @since 26/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

use Symfony\Component\Security\Core\Security;

class IndexViewer extends AbstractViewer
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
        $actions = array();
        $securityContext = $this->container->get('security.context');
        $currentUser = $securityContext->getToken()->getUser();
        foreach($this->getConfig()->get('actions') as $action) {
            $currentRole = 'ROLE_'.strtoupper($action['route']);
            $resource = array();
            $resource['entity'] = $this->getResourceName();
            if(in_array($currentRole, $currentUser->getRoles()) && $securityContext->isGranted('WORKFLOW', $resource)){
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