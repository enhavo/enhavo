<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 02.09.14
 * Time: 18:26
 */

namespace esperanto\ReferenceBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactory;

class ReferenceResolver
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getReference(Request $request)
    {
        if($request->get('id') == 'preview' && $request->getMethod() === 'POST')
        {
            $reference = $this->getPreviewReference($request);
        } else {
            $reference = $this->getLiveReference($request);
        }

        return $reference;
    }

    public function getPreviewReference(Request $request)
    {
        /** @var $formFactory FormFactory */
        $formFactory = $this->container->get('form.factory');
        $form = $formFactory->create('esperanto_reference_reference');
        $form->submit($request);
        return $form->getData();
    }

    public function getLiveReference(Request $request)
    {
        /** @var $doctrine EntityManager */
        $doctrine = $this->container->get('doctrine');
        $id = $request->get('id');

        $repository = $this->container->get('esperanto_reference.repository.reference');
        $reference = $repository->find($id);
        return $reference;
    }
} 