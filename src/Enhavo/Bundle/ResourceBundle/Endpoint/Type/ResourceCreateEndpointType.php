<?php

namespace Enhavo\Bundle\ResourceBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Input\Input;
use Enhavo\Bundle\ResourceBundle\Input\InputFactory;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourceCreateEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly InputFactory $inputFactory,
        private readonly ResourceManager $resourceManager,
        private readonly VueForm $vueForm,
        private readonly RouteResolverInterface $routeResolver,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var Input $input */
        $input = $this->inputFactory->create($options['input']);

        $resource = $input->createResource();

        $form = $input->getForm();

        if ($form) {
            $form->setData($resource);
            $form->handleRequest($request);

            $context->set('form', $form);
            $context->set('resource', $resource);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $this->resourceManager->save($resource);
                    $context->setStatusCode(201);

                    $redirectRoute = $this->routeResolver->getRoute('update', ['api' => false]);
                    if ($redirectRoute) {
                        $data->set('redirect', $this->generateUrl($redirectRoute, ['id' => $resource->getId()]));
                    }
                } else {
                    $context->setStatusCode(400);
                }
            }

            $data->set('form', $this->vueForm->createData($form->createView()));
        }

        $viewData = $input->getViewData($resource);
        $data->add($viewData);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('input');
    }

    public static function getName(): ?string
    {
        return 'resource_create';
    }
}
