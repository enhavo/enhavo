<?php
/**
 * ResourceController.php
 *
 * @since 01/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Controller;

use esperanto\AdminBundle\Admin\BaseAdmin;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Form\Test\FormInterface;

class ResourceController extends BaseController
{
    protected $admin;

    /**
     * {@inheritdoc}
     */
    public function createAction(Request $request)
    {
        if(!$this->getAdmin()->isActionGranted(BaseAdmin::GRANTED_ACTION_CREATE)) {
            throw new AccessDeniedHttpException();
        }

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

        $view = $this
            ->view()
            ->setTemplate($this->config->getTemplate('create.html'))
            ->setData(array(
                'form' => $form->createView(),
                'data' => $resource,
                'view' => $this->getAdmin()->createView()
            ))
        ;

        return $this->handleView($view);
    }

    /**
     * {@inheritdoc}
     */
    public function updateAction(Request $request)
    {
        if(!$this->getAdmin()->isActionGranted(BaseAdmin::GRANTED_ACTION_EDIT)) {
            throw new AccessDeniedHttpException();
        }

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

        $view = $this
            ->view()
            ->setTemplate($this->config->getTemplate('update.html'))
            ->setData(array(
                'form' => $form->createView(),
                'data' => $resource,
                'view' => $this->getAdmin()->createView()
            ))
        ;

        return $this->handleView($view);
    }

    /**
     * {@inheritdoc}
     */
    public function indexAction(Request $request)
    {
        if(!$this->getAdmin()->isActionGranted(BaseAdmin::GRANTED_ACTION_INDEX)) {
            throw new AccessDeniedHttpException();
        }

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

        $view = $this
            ->view()
            ->setTemplate($this->config->getTemplate('index.html'))
            ->setData(array(
                'data' => $resources,
                'view' => $this->getAdmin()->createView()
            ))
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

    protected function getAdmin()
    {
        if($this->admin == null) {
            $request = $this->container->get('request');
            $adminServiceName = $request->attributes->get('_admin');
            $this->admin = $this->container->get($adminServiceName);
        }
        return $this->admin;
    }

    /**
     * @param object|null $resource
     *
     * @return FormInterface
     */
    protected function getBatchUpdateForm()
    {
        return;
    }
}