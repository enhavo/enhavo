<?php
/**
 * ResourceController.php
 *
 * @since 01/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\AppBundle\Config\ConfigParser;
use Enhavo\Bundle\AppBundle\Exception\BadMethodCallException;
use Enhavo\Bundle\AppBundle\Exception\PreviewException;
use Enhavo\Bundle\AppBundle\Security\Roles\RoleUtil;
use Enhavo\Bundle\UserBundle\Entity\User;
use Enhavo\Bundle\AppBundle\Viewer\CreateViewer;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController as BaseController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ResourceController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function createAction(Request $request)
    {
        $config = $this->get('viewer.config')->parse($request);
        /** @var CreateViewer $viewer */
        $viewer = $this->get('viewer.factory')->create($config->getType(), 'create');
        $viewer->setBundlePrefix($this->config->getBundlePrefix());
        $viewer->setResourceName($this->config->getResourceName());
        $viewer->setConfig($config);

        $sortingConfig = $viewer->getSorting();

        $resource = $this->createNew();
        $form = $this->getForm($resource);

        $method = $request->getMethod();
        if (in_array($method, array('POST', 'PUT', 'PATCH'))) {
            if($form->handleRequest($request)->isValid()) {
                if ($sortingConfig['sortable']) {
                    $this->generateInitialSortingValue($resource, $sortingConfig);
                }
                $this->domainManager->create($resource);
                $this->dispatchEvent('enhavo_app.create', $resource, array('action' => 'create'));
                return new Response();
            }

            $view = $this->view($form);
            $view->setFormat('json');
            return $this->handleView($view);
        }

        $viewer->setResource($resource);
        $viewer->setForm($form);
        $viewer->dispatchEvent('');

        $view = $this
            ->view()
            ->setTemplate($this->config->getTemplate('create.html'))
            ->setData($viewer->getParameters())
        ;

        return $this->handleView($view);
    }

    /**
     * {@inheritdoc}
     */
    public function updateAction(Request $request)
    {
        $config = $this->get('viewer.config')->parse($request);
        $viewer = $this->get('viewer.factory')->create($config->getType(), 'edit');
        $viewer->setBundlePrefix($this->config->getBundlePrefix());
        $viewer->setResourceName($this->config->getResourceName());
        $viewer->setConfig($config);

        $resource = $this->findOr404($request);
        $roleUtil = new RoleUtil();
        $roleName = $roleUtil->getRoleName($resource, RoleUtil::ACTION_UPDATE);
        if(!$this->isGranted($roleName, $resource)) {
            return new JsonResponse(null, 403);
        }
        $form = $this->getForm($resource);
        $method = $request->getMethod();

        if (in_array($method, array('POST', 'PUT', 'PATCH'))) {
            if($form->submit($request, !$request->isMethod('PATCH'))->isValid()) {
                $this->dispatchEvent('enhavo_app.pre_update', $resource, array('action' => 'pre_update'));
                $this->domainManager->update($resource);
                $this->dispatchEvent('enhavo_app.update', $resource, array('action' => 'update'));
                return new Response();
            }

            $view = $this->view($form);
            $view->setFormat('json');
            return $this->handleView($view);
        }

        $viewer->setResource($resource);
        $viewer->setForm($form);
        $viewer->dispatchEvent('');

        $view = $this
            ->view()
            ->setTemplate($this->config->getTemplate('update.html'))
            ->setData($viewer->getParameters())
        ;

        return $this->handleView($view);
    }

    public function indexAction(Request $request)
    {
        $config = $this->get('viewer.config')->parse($request);
        $viewer = $this->get('viewer.factory')->create($config->getType(), 'index');
        $viewer->setBundlePrefix($this->config->getBundlePrefix());
        $viewer->setResourceName($this->config->getResourceName());
        $viewer->setConfig($config);

        $viewer->dispatchEvent('');

        $view = $this
            ->view()
            ->setTemplate($viewer->getTemplate())
            ->setData($viewer->getParameters())
        ;

        return $this->handleView($view);
    }

    public function previewAction(Request $request)
    {
        $config = $this->get('viewer.config')->parse($request);
        $viewer = $this->get('viewer.factory')->create($config->getType(), 'preview');
        $viewer->setBundlePrefix($this->config->getBundlePrefix());
        $viewer->setResourceName($this->config->getResourceName());
        $viewer->setConfig($config);

        $resource = $this->createNew();
        $form = $this->getForm($resource);
        $form->handleRequest($request);

        $strategyName = $viewer->getStrategyName();
        $strategy = $this->get('enhavo_app.preview.strategy_resolver')->getStrategy($strategyName);
        $response = $strategy->getPreviewResponse($resource, $viewer->getConfig());
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function tableAction(Request $request)
    {
        $config = $this->get('viewer.config')->parse($request);
        $viewer = $this->get('viewer.factory')->create($config->getType(), 'table');
        $viewer->setBundlePrefix($this->config->getBundlePrefix());
        $viewer->setResourceName($this->config->getResourceName());
        $viewer->setConfig($config);

        //fire event for permission
        $criteria = $this->config->getCriteria();
        $sorting = $this->config->getSorting();
        $repository = $this->getRepository();

        if ($this->config->isPaginated()) {
            $resources = $this->resourceResolver->getResource(
                $repository,
                'createPaginator',
                array($criteria, $sorting)
            );
            $resources->setCurrentPage($request->get('page', 1), true, true);
            $resources->setMaxPerPage($this->config->getPaginationMaxPerPage());
        } else {
            $resources = $this->resourceResolver->getResource(
                $repository,
                'findBy',
                array($criteria, $sorting, $this->config->getLimit())
            );
        }

        $viewer->setResource($resources);
        $viewer->dispatchEvent('');

        $view = $this
            ->view()
            ->setTemplate($this->config->getTemplate('index.html'))
            ->setData($viewer->getParameters())
        ;

        return $this->handleView($view);
    }

    /**
     *
     */
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

    public function deleteAction(Request $request)
    {
        $this->isGrantedOr403('delete');
        $resource = $this->findOr404($request);
        $this->dispatchEvent('enhavo_app.delete', $resource, array('action' => 'delete'));
        $this->domainManager->delete($this->findOr404($request));
        return new Response();
    }

    public function moveAfterAction(Request $request)
    {
        $config = $this->get('viewer.config')->parse($request);
        $viewer = $this->get('viewer.factory')->create($config->getType(), 'sorting');
        $viewer->setBundlePrefix($this->config->getBundlePrefix());
        $viewer->setResourceName($this->config->getResourceName());
        $viewer->setConfig($config);

        $parameters = $viewer->getParameters();
        if (isset($parameters['sorting'])) {
            $sorting = strtoupper($parameters['sorting']);
        } else {
            throw new InvalidConfigurationException('Incompatible viewer type "' . get_class($viewer) . '" for route type move_after: expected field "sorting" in viewer->getParameters()');
        }
        $property = $this->config->getSortablePosition();
        if (!$property) {
            return new JsonResponse(array('success' => false));
        }

        $resource = $this->findOr404($request);
        $targetId =  $request->get('target');
        if (!$targetId) {
            throw new \InvalidArgumentException('Missing parameter "target" for route type move_after');
        }
        $targetResource = $this->findOr404ById($targetId);

        $accessor = PropertyAccess::createPropertyAccessor();
        $resourceValue = $accessor->getValue(
            $resource,
            $property
        );
        $targetValue = $accessor->getValue(
            $targetResource,
            $property
        );
        if ($resourceValue === null || $targetValue === null || $resourceValue == $targetValue) {
            $this->recalculateSortingProperty($property, $sorting);
            $resourceValue = $accessor->getValue(
                $resource,
                $property
            );
            $targetValue = $accessor->getValue(
                $targetResource,
                $property
            );
        }

        if (($sorting == 'ASC') && ($resourceValue > $targetValue)) {
            $targetPosition = $targetValue + 1;
        } elseif (($sorting == 'DESC') && ($resourceValue < $targetValue)) {
            $targetPosition = $targetValue - 1;
        } else {
            $targetPosition = $targetValue;
        }

        $this->moveToPosition($resource, $targetPosition, $property, $sorting);

        return new JsonResponse(array('success' => true));
    }

    public function moveToPageAction(Request $request)
    {
        $config = $this->get('viewer.config')->parse($request);
        $viewer = $this->get('viewer.factory')->create($config->getType(), 'sorting');
        $viewer->setBundlePrefix($this->config->getBundlePrefix());
        $viewer->setResourceName($this->config->getResourceName());
        $viewer->setConfig($config);

        $parameters = $viewer->getParameters();
        if (isset($parameters['sorting'])) {
            $sorting = strtoupper($parameters['sorting']);
        } else {
            throw new InvalidConfigurationException('Incompatible viewer type "' . get_class($viewer) . '" for route type move_to_page: expected field "sorting" in viewer->getParameters()');
        }
        $property = $this->config->getSortablePosition();
        $paginate = $this->config->getPaginationMaxPerPage();
        if (!$property || !$paginate) {
            return new JsonResponse(array('success' => false));
        }

        $resource = $this->findOr404($request);
        $page = $request->get('page');
        $top = $request->get('top');

        /** @var EntityRepository $repository */
        $repository = $this->getRepository();

        $pageOffset = ($page - 1) * $paginate + ($top ? 0 : ($paginate -1));

        $target = $repository->createQueryBuilder('r')
            ->orderBy('r.' . $property, $sorting)
            ->setFirstResult($pageOffset)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$target) {
            // Last element
            $target = $repository->createQueryBuilder('r')
                ->addOrderBy('r.' . $property, $sorting == 'ASC' ? 'DESC' : 'ASC')
                ->addOrderBy('r.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        }
        if (!$target) {
            return new JsonResponse(array('success' => false));
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        $resourceValue = $accessor->getValue(
            $resource,
            $property
        );
        $targetValue = $accessor->getValue(
            $target,
            $property
        );


        if ($resourceValue === null || $targetValue === null || $resourceValue == $targetValue) {
            $this->recalculateSortingProperty($property, $sorting);
            $targetValue = $accessor->getValue(
                $target,
                $property
            );
        }

        $this->moveToPosition($resource, $targetValue, $property, $sorting);

        return new JsonResponse(array('success' => true));
    }

    protected function moveToPosition($resource, $position, $property, $sorting)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getRepository();
        $accessor = PropertyAccess::createPropertyAccessor();
        /** @var ObjectManager $manager */
        $manager = $this->get($this->config->getServiceName('manager'));

        $resourceId = $accessor->getValue(
            $resource,
            'id'
        );
        $resourceValue = $accessor->getValue(
            $resource,
            $property
        );
        $max = max($resourceValue, $position);
        $min = min($resourceValue, $position);
        $movement = ($resourceValue < $position) ? -1 : +1;

        $toMove = $repository->createQueryBuilder('r')
            ->where('r.' . $property . ' <= :max')
            ->andWhere('r.' . $property . ' >= :min')
            ->setParameter('max', $max)
            ->setParameter('min', $min)
            ->orderBy('r.' . $property, $sorting)
            ->getQuery()
            ->getResult();

        foreach($toMove as $object) {
            $objectId = $accessor->getValue(
                $object,
                'id'
            );
            if ($objectId == $resourceId) {
                continue;
            }
            $objectValue = $accessor->getValue(
                $object,
                $property
            );
            $objectValue += $movement;
            $accessor->setValue(
                $object,
                $property,
                $objectValue
            );
            $manager->persist($object);
        }
        $accessor->setValue(
            $resource,
            $property,
            $position
        );
        $manager->flush();
    }

    protected function generateInitialSortingValue($resource, $sortingConfig)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getRepository();
        $accessor = PropertyAccess::createPropertyAccessor();
        /** @var ObjectManager $manager */
        $manager = $this->get($this->config->getServiceName('manager'));
        $property = $sortingConfig['position'];

        if ($sortingConfig['initial'] == 'min') {
            // Value is 0, but we need to move all other elements one up
            $existingResources = $repository->findAll();
            if ($existingResources) {
                foreach($existingResources as $existingResource) {
                    $value = $accessor->getValue(
                        $existingResource,
                        $property
                    );
                    $accessor->setValue(
                        $existingResource,
                        $property,
                        $value + 1
                    );
                    $manager->persist($existingResource);
                }
                $manager->flush();
            }
            $newValue = 0;
        } else {
            // Initial value is maximum of other elements + 1
            $maxResource = $repository->createQueryBuilder('r')
                ->orderBy('r.' . $property, 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
            if (!$maxResource) {
                $newValue = 0;
            } else {
                $maxValue = $accessor->getValue(
                    $maxResource,
                    $property
                );
                $newValue = $maxValue + 1;
            }
        }
        $accessor->setValue(
            $resource,
            $property,
            $newValue
        );
    }

    protected function recalculateSortingProperty($property, $sorting)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getRepository();
        /** @var ObjectManager $manager */
        $manager = $this->get($this->config->getServiceName('manager'));
        $accessor = PropertyAccess::createPropertyAccessor();

        if (!in_array($sorting, array('ASC', 'DESC'))) {
            $sorting = 'DESC';
        }

        $allEntities = $repository->findBy(array(), array($property => $sorting));
        if ($sorting == 'DESC') {
            $allEntities = array_reverse($allEntities);
        }
        $i = 0;

        foreach($allEntities as $resource) {
            $accessor->setValue(
                $resource,
                $property,
                $i++
            );
            $manager->persist($resource);
        }
        $manager->flush();
    }

    protected function findOr404ById($id)
    {
        $result = $this->resourceResolver->getResource(
            $this->getRepository(),
            'findOneBy',
            array($this->config->getCriteria(array('id' => $id)))
        );
        if (!$result) {
            throw new NotFoundHttpException(
                sprintf(
                    'Requested %s does not exist with these criteria: %s.',
                    $this->config->getResourceName(),
                    json_encode(array($this->config->getCriteria(array('id' => $id))))
                )
            );
        }
        return $result;
    }

    protected function dispatchEvent($eventName, $subject = null, $arguments = array())
    {
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch($eventName, new GenericEvent($subject, $arguments));
    }
}