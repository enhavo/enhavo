<?php
/**
 * ResourceController.php
 *
 * @since 01/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Controller;

use esperanto\AdminBundle\Exception\BadMethodCallException;
use esperanto\AdminBundle\Exception\PreviewException;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourceController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function createAction(Request $request)
    {
        $config = $this->get('viewer.config')->parse($request);
        $viewer = $this->get('viewer.factory')->create($config->getType());
        $viewer->setConfig($config);

        $resource = $this->createNew();
        $form = $this->getForm($resource);

        $method = $request->getMethod();
        if (in_array($method, array('POST', 'PUT', 'PATCH'))) {
            if($form->handleRequest($request)->isValid()) {
                $this->domainManager->create($resource);
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
        $viewer = $this->get('viewer.factory')->create($config->getType());
        $viewer->setConfig($config);

        $resource = $this->findOr404($request);
        $form = $this->getForm($resource);
        $method = $request->getMethod();

        if (in_array($method, array('POST', 'PUT', 'PATCH'))) {
            if($form->submit($request, !$request->isMethod('PATCH'))->isValid()) {
                $this->domainManager->update($resource);
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
        throw new BadMethodCallException(sprintf(
            'Don\'t use the indexAction in class, use "appAction" action instead ', get_class($this)
        ));
    }

    public function previewAction(Request $request)
    {
        $resource = $this->createNew();
        $form = $this->getForm($resource);
        $form->handleRequest($request);
        return $this->invokePreview($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function tableAction(Request $request)
    {
        $config = $this->get('viewer.config')->parse($request);
        $viewer = $this->get('viewer.factory')->create($config->getType());
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

    /**
     * @param $resource
     * @return Response
     * @throws PreviewException
     */
    protected function invokePreview($resource)
    {
        $map = $this->container->getParameter('cmf_routing.controllers_by_class');
        $controllerDefinition = null;
        foreach ($map as $class => $value) {
            if ($resource instanceof $class) {
                $controllerDefinition = $value;
                break;
            }
        }

        if($controllerDefinition === null) {
            throw new PreviewException(
                sprintf(
                    'No controller found for resource, did you add "%s" to cmf_routing.dynamic.controller_by_class in your configuration?',
                    get_class($resource)
                )
            );
        }

        try {
            $request = new Request(array(), array(), array('_controller' => $controllerDefinition));
            $controller = $this->container->get('debug.controller_resolver')->getController($request);
            $response = call_user_func_array($controller, array($resource));
        } catch(\Exception $e) {
            throw new PreviewException(
                sprintf(
                    'Something went wrong while trying to invoke the controller "%s", this "%s" was thrown before with message: %s',
                    $controllerDefinition,
                    get_class($e),
                    $e->getMessage()
                )
            );
        }

        return $response;
    }
}