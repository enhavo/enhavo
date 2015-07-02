<?php

namespace enhavo\CategoryBundle\Controller;

use enhavo\AdminBundle\Controller\ResourceController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use enhavo\AdminBundle\Admin\BaseAdmin;

class ResourceController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function updateAction(Request $request)
    {
        if(!$this->getAdmin()->isActionGranted(BaseAdmin::GRANTED_ACTION_EDIT)) {
            throw new AccessDeniedHttpException();
        }

        $resource = $this->resourceResolver->getResource(
            $this->getRepository(),
            'findOneBy',
            array($this->config->getCriteria(array('name' => $request->get('name')))));

        if(empty($resource)) {
            $resource = $this->createNew();
            $resource->setName($request->get('name'));
        }

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
}
