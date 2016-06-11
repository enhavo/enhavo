<?php

namespace Enhavo\Bundle\CategoryBundle\Controller;

use Enhavo\Bundle\AppBundle\Viewer\EditViewer;
use Enhavo\Bundle\CategoryBundle\Model\CategoryInterface;
use Enhavo\Bundle\CategoryBundle\Model\CollectionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Enhavo\Bundle\AppBundle\Controller\ResourceController;

class CollectionController extends ResourceController
{
    /**
     * {@inheritdoc}
     */
    public function updateAction(Request $request)
    {
        $config = $this->get('viewer.config')->parse($request);
        /** @var EditViewer $viewer */
        $viewer = $this->get('viewer.factory')->create($config->getType(), 'edit');
        $viewer->setBundlePrefix($this->config->getBundlePrefix());
        $viewer->setResourceName($this->config->getResourceName());
        $viewer->setConfig($config);
        $viewer->setIdentifier('collection');
        $viewer->setIdentifierProperty('name');

        $collection = $request->get('collection');
        $resource = $this->findCollection($collection);
        if(empty($resource)) {
            /** @var CollectionInterface $resource */
            $resource = $this->get('enhavo_category.factory.collection')->createNew();
            $resource->setName($collection);
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

        $view = $this
            ->view()
            ->setTemplate($this->config->getTemplate('update.html'))
            ->setData($viewer->getParameters())
        ;

        return $this->handleView($view);
    }

    public function findCollection($name)
    {
        $resource = $this->getRepository()->findOneBy([
            'name' => $name
        ]);
        return $resource;
    }
}
