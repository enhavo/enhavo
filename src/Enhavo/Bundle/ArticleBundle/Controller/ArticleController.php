<?php

namespace Enhavo\Bundle\ArticleBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus;

class ArticleController extends ResourceController
{
    public function showResource($article)
    {
        return $this->render('EnhavoArticleBundle:Article:show.html.twig', array(
            'data' => $article
        ));
    }

    /**
     * {@inheritdoc}
     */
   /* public function createAction(Request $request)
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
                //Workflowstatus setzen
                 $workflow_status = new WorkflowStatus();
                 $workflow_status->setBundle('article');
                 $workflow_status->setReference($resource->getId());
                 $workflow_status->setNode($resource->getWorkflow()->getStartNode());

                 $em = $this->getDoctrine()->getManager();
                 $em->persist($workflow_status);
                 $em->flush();

                 $resource->setWorkflowStatus($workflow_status);
                $em = $this->getDoctrine()->getManager();
                $em->persist($resource);
                $em->flush();
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
    }*/

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
        $form = $this->getForm($resource);
        $method = $request->getMethod();

        if (in_array($method, array('POST', 'PUT', 'PATCH'))) {
            if($form->submit($request, !$request->isMethod('PATCH'))->isValid()) {
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
}