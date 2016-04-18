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
use Sylius\Bundle\ResourceBundle\Controller\ResourceController as BaseController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ResourceController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function createAction(Request $request)
    {
        $config = $this->get('viewer.config')->parse($request);
        $viewer = $this->get('viewer.factory')->create($config->getType(), 'create');
        $viewer->setBundlePrefix($this->config->getBundlePrefix());
        $viewer->setResourceName($this->config->getResourceName());
        $viewer->setConfig($config);

        $parameters = $viewer->getParameters();
        if (isset($parameters['sorting'])) {
            $sortingConfig = $parameters['sorting'];
        } else {
            throw new InvalidConfigurationException('Incompatible viewer type "' . get_class($viewer) . '" for route create: expected field "sorting" in viewer->getParameters()');
        }

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
        //$this->dispatchEvent('enhavo_app.pre_update', $resource, array('action' => 'pre_update'));
        if(!$this->isGranted('WORKFLOW', $resource)) {
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

        return $this->render($viewer->getTemplate(), $viewer->getParameters());
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

        $strategyName = $config->get('strategy');
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
        $criteria = $this->config->getCriteria();
        $sorting = $this->config->getSorting();
        $form = $this->getBatchUpdateForm();
        $repository = $this->getRepository();
        $method = $request->getMethod();

        if (in_array($method, array('POST', 'PUT', 'PATCH'))) {
            if($form->submit($request, !$request->isMethod('PATCH'))->isValid()) {
                $resources = $form->getData();
                foreach($resources as $resource) {
                    $this->domainManager->update($resource);
                }
                return new Response();
            }

            $view = $this->view($form);
            $view->setFormat('json');
            return $this->handleView($view);
        } else {
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
        }

        $view = $this
            ->view()
            ->setTemplate($this->config->getTemplate('update.html'))
            ->setData(array(
                'form' => $form->createView(),
                'data' => $resources,
                'view' => $this->getAdmin()->createView()
            ))
        ;

        return $this->handleView($view);
    }

    public function deleteAction(Request $request)
    {
        $this->isGrantedOr403('delete');
        $resource = $this->findOr404($request);
        $this->dispatchEvent('enhavo_app.delete', $resource, array('action' => 'delete'));
        $this->domainManager->delete($this->findOr404($request));
        return new Response();
    }

    /**
     * @inheritdoc
     */
    protected function move(Request $request, $movement)
    {
        $config = $this->get('viewer.config')->parse($request);
        $viewer = $this->get('viewer.factory')->create($config->getType(), 'sorting');
        $viewer->setBundlePrefix($this->config->getBundlePrefix());
        $viewer->setResourceName($this->config->getResourceName());
        $viewer->setConfig($config);

        $parameters = $viewer->getParameters();
        if (isset($parameters['sorting'])) {
            $sorting = $parameters['sorting'];
        } else {
            throw new InvalidConfigurationException('Incompatible viewer type "' . get_class($viewer) . '" for route type move_up/move_down: expected field "sorting" in viewer->getParameters()');
        }

        $property = $this->config->getSortablePosition();
        if (!$property) {
            return new JsonResponse(array('success' => false));
        }

        /** @var EntityRepository $repository */
        $repository = $this->getRepository();
        $accessor = PropertyAccess::createPropertyAccessor();
        /** @var ObjectManager $manager */
        $manager = $this->get($this->config->getServiceName('manager'));

        $resource = $this->findOr404($request);
        $resourceValue = $accessor->getValue(
            $resource,
            $property
        );

        if ($sorting == 'DESC') {
            $comparator = $movement > 0 ? '>' : '<';
            $orderBy = $movement > 0 ? 'ASC' : 'DESC';
        } else {
            $comparator = $movement > 0 ? '<' : '>';
            $orderBy = $movement > 0 ? 'DESC' : 'ASC';
        }
        $swapTarget = $repository->createQueryBuilder('r')
            ->where('r.' . $property . ' ' . $comparator . ' :value')
            ->setParameter('value', $resourceValue)
            ->orderBy('r.' . $property, $orderBy)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        if (!$swapTarget) {
            return new JsonResponse(array('success' => true, 'firstOrLastElement' => true));
        }

        $swapTargetValue = $accessor->getValue(
            $swapTarget,
            $property
        );

        $accessor->setValue(
            $resource,
            $property,
            $swapTargetValue
        );
        $accessor->setValue(
            $swapTarget,
            $property,
            $resourceValue
        );

        $manager->persist($resource);
        $manager->persist($swapTarget);
        $manager->flush();

        return new JsonResponse(array('success' => true, 'firstOrLastElement' => false));
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

    protected function dispatchEvent($eventName, $subject = null, $arguments = array())
    {
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch($eventName, new GenericEvent($subject, $arguments));
    }
}