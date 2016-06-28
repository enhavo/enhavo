<?php
/**
 * BatchManager.php
 *
 * @since 28/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Component\Resource\Metadata\MetadataInterface;

class BatchManager
{
    public function executeBatch(RequestConfiguration $requestConfiguration, MetadataInterface $metadata)
    {

    }

    public function batchAction(Request $request)
    {
        $repository = $this->getRepository();

        $targetResources = $request->request->get('batchActionTargets');
        $action = $request->request->get('batchActionCommand');

        $resources = array();
        foreach($targetResources as $resourceId) {
            $resources []= $repository->find($resourceId);
        }
        $methodName = "batchAction" . ucfirst($action);
        if (call_user_func(array($this, $methodName), $resources)) {
            return new JsonResponse(array('success' => true));
        } else {
            return new JsonResponse(array('success' => false));
        }
    }

    public function batchActionDelete($resources)
    {
        $this->isGrantedOr403('delete');
        foreach ($resources as $resource) {
            $this->domainManager->delete($resource);
        }
        $this->get('doctrine.orm.entity_manager')->flush();

        return true;
    }

    protected function parseBatchActions()
    {
        $authorizationChecker = $this->container->get('security.authorization_checker');

        $batch_actions = $this->getConfig()->get('table.batch.actions');
        if (!$batch_actions) {
            return array();
        }

        if (count($batch_actions) > 0) {
            if (!isset($batch_actions[self::BATCH_ACTION_NONE_SELECTED])) {
                $batch_actions[self::BATCH_ACTION_NONE_SELECTED] = array(
                    'label'                 => 'table.batch.label.selectAction',
                    'confirm_message'       => '',
                    'translation_domain'    => 'EnhavoAppBundle'
                );
            }

            $translator = $this->container->get('translator');
            $slugifier = new Slugifier();
            $pos = 100;
            $batch_actions_parsed = array();
            foreach($batch_actions as $command => $value) {
                if (isset($value['permission']) && $value['permission']) {
                    if (!$authorizationChecker->isGranted($value['permission'])) {
                        continue;
                    }
                }

                $command_parsed = $slugifier->slugify($command);
                $action_parsed = array();

                if (isset($value['translation_domain']) && $value['translation_domain']) {
                    $domain = $value['translation_domain'];
                } else {
                    $domain = $this->getTranslationDomain();
                }

                if (isset($value['label']) && $value['label']) {
                    $action_parsed['label'] = $translator->trans($value['label'], array(), $domain);
                } else {
                    $action_parsed['label'] = $command;
                }

                if (isset($value['confirm_message']) && $value['confirm_message']) {
                    $action_parsed['confirm_message'] = $translator->trans($value['confirm_message'], array(), $domain);
                } else {
                    $action_parsed['confirm_message'] = $translator->trans('table.batch.message.confirm.generic', array('%command%' => $command), 'EnhavoAppBundle');
                }

                if (isset($value['position']) && is_int($value['position']) && ($value['position'] >= 0)) {
                    $action_parsed['position'] = $value['position'];
                } else {
                    if ($command == self::BATCH_ACTION_NONE_SELECTED) {
                        $action_parsed['position'] = -1;
                    } else {
                        $action_parsed['position'] = $pos++;
                    }
                }
                $batch_actions_parsed[$command_parsed] = $action_parsed;
            }

            uasort($batch_actions_parsed,
                function($a, $b) {
                    if ($a['position'] == $b['position']) {
                        return 0;
                    }
                    return $a['position'] < $b['position'] ? -1 : 1;
                }
            );

            return $batch_actions_parsed;
        }

        return array();
    }

    public function getBatchActionRoute()
    {
        return $this->getConfig()->get('table.batch.route');
    }

    public function getHasBatchActions()
    {
        $batch_actions = $this->getBatchActions();
        $route = $this->getConfig()->get('table.batch.route');
        if (!$this->container->get('router')->getRouteCollection()->get($route)) {
            return false;
        }

        return $batch_actions && (count($batch_actions) > 1);
    }

    public function getBatchActions()
    {
        if (!$this->batch_actions) {
            $this->batch_actions = $this->parseBatchActions();
        }
        return $this->batch_actions;
    }

}