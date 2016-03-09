<?php

namespace Enhavo\Bundle\WorkflowBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class WorkflowController extends ResourceController
{
    public function updateAction(Request $request)
    {
        $workflow = $this->getDoctrine()
            ->getRepository('EnhavoWorkflowBundle:Workflow')
            ->find($request->get('id'));


        $config = $this->get('viewer.config')->parse($request);
        $viewer = $this->get('viewer.factory')->create($config->getType(), 'edit');
        $viewer->setBundlePrefix($this->config->getBundlePrefix());
        $viewer->setResourceName($this->config->getResourceName());
        $viewer->setConfig($config);

        $resource = $this->findOr404($request);
        $form = $this->createForm('Enhavo\Bundle\WorkflowBundle\Form\Type\WorkflowType', $workflow);
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
