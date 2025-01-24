<?php

namespace Enhavo\Bundle\ResourceBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Authorization\Permission;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
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
        private readonly ResourceExpressionLanguage $expressionLanguage,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var Input $input */
        $input = $this->inputFactory->create($options['input']);

        $this->denyAccessUnlessGranted(new Permission($input->getResourceName(), $options['permission']));

        $resource = $input->createResource();

        $form = $input->createForm($resource);

        if ($form) {
            $form->handleRequest($request);

            $context->set('form', $form);
            $context->set('resource', $resource);

            $data->set('url', $request->getPathInfo());

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $this->resourceManager->save($resource);
                    $input->setResource($resource);
                    $context->setStatusCode(201);

                    $form = $input->createForm($resource);

                    $redirectRoute = $this->routeResolver->getRoute('update', ['api' => false]) ?? $options['update_route'];
                    if ($redirectRoute === null) {
                        throw new \Exception('Can\'t find update route, please provide a route over the "update_route" option');
                    }
                    $redirectRouteParameters = array_merge(['id' => $resource->getId()], $this->expressionLanguage->evaluateArray($options['update_route_parameters']));

                    $apiRoute = $this->routeResolver->getRoute('update', ['api' => true])  ?? $options['update_api_route'];
                    if ($apiRoute === null) {
                        throw new \Exception('Can\'t find update api route, please provide a route over the "update_api_route" option');
                    }
                    $apiRouteParameters = array_merge(['id' => $resource->getId()], $this->expressionLanguage->evaluateArray($options['update_api_route_parameters']));

                    $data->set('url', $this->generateUrl($apiRoute, $apiRouteParameters));
                    if ($redirectRoute) {
                        $data->set('redirect', $this->generateUrl($redirectRoute, $redirectRouteParameters));
                    }
                } else {
                    $context->setStatusCode(400);
                }
            }

            $formFields = $request->get('form-fields') ? explode(',', $request->get('form-fields')) : null;
            $data->set('form', $this->vueForm->createData($form->createView(), $formFields));
        }

        $viewData = $input->getViewData($resource);
        $data->add($viewData);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'update_route' => null,
            'update_route_parameters' => [],
            'update_api_route' => null,
            'update_api_route_parameters' => [],
            'permission' => Permission::CREATE,
        ]);

        $resolver->setRequired('input');
    }

    public static function getName(): ?string
    {
        return 'resource_create';
    }
}
