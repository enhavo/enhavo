<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Path;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Authorization\Permission;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\Form\FormDescriber;
use Enhavo\Bundle\ResourceBundle\Form\FormNormalizerInterface;
use Enhavo\Bundle\ResourceBundle\Input\Input;
use Enhavo\Bundle\ResourceBundle\Input\InputFactory;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourceCreateEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly InputFactory $inputFactory,
        private readonly ResourceManager $resourceManager,
        private readonly FormNormalizerInterface $formNormalizer,
        private readonly RouteResolverInterface $routeResolver,
        private readonly ResourceExpressionLanguage $expressionLanguage,
        private readonly FormDescriber $formDescriber,
        private readonly FormNormalizerInterface $formErrorNormalizer,
        private readonly FormNormalizerInterface $formDataNormalizer,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var Input $input */
        $input = $this->inputFactory->create($options['input']);

        $this->denyAccessUnlessGranted($input->getPermission($options['permission']));

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
                    if (null === $redirectRoute) {
                        throw new \Exception('Can\'t find update route, please provide a route over the "update_route" option');
                    }
                    $redirectRouteParameters = array_merge(['id' => $resource->getId()], $this->expressionLanguage->evaluateArray($options['update_route_parameters']));

                    $apiRoute = $this->routeResolver->getRoute('update', ['api' => true]) ?? $options['update_api_route'];
                    if (null === $apiRoute) {
                        throw new \Exception('Can\'t find update api route, please provide a route over the "update_api_route" option');
                    }
                    $apiRouteParameters = array_merge(['id' => $resource->getId()], $this->expressionLanguage->evaluateArray($options['update_api_route_parameters']));

                    $data->set('url', $this->generateUrl($apiRoute, $apiRouteParameters));
                    if ($redirectRoute) {
                        $data->set('redirect', $this->generateUrl($redirectRoute, $redirectRouteParameters));
                    }
                    $data->set('data', $this->formDataNormalizer->normalize($form));
                } else {
                    $data->set('errors', $this->formErrorNormalizer->normalize($form));
                    $context->setStatusCode(400);
                }
            }

            $formFields = $request->query->get('form-fields') ? explode(',', $request->query->get('form-fields')) : null;
            $data->set('form', $this->formNormalizer->normalize($form, ['fields' => $formFields]));
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

    public function describe($options, Path $path): void
    {
        /** @var Input $input */
        $input = $this->inputFactory->create($options['input']);
        $form = $input->createForm();
        $schemaName = str_replace('\\', '', $form->getConfig()->getType()->getInnerType()::class);

        $this->formDescriber->describe($form, $path->getDocumentation()->components()->schema($schemaName));

        $path->method('get')
            ->tags([$input->getResourceName()])
            ->parameter('form-fields')
                ->in('query')
                ->description('Comma separated list of form fields')
                ->schema()
                    ->string()->end()
                ->end()
            ->end()
            ->response('200')
                ->description('Data')
                ->content()
                    ->schema()
                        ->object()
                            ->property('actions', 'array')->items()->object()->end()->end()->end()
                            ->property('actionsSecondary', 'array')->items()->object()->end()->end()->end()
                            ->property('form', 'object')->end()
                            ->property('metadata', 'object')->end()
                            ->property('resource', 'object')->end()
                            ->property('tabs', 'object')->end()
                            ->property('url', 'string')->end()
        ;

        $path->method('post')
            ->tags([$input->getResourceName()])
            ->parameter('form-fields')
                ->in('query')
                ->description('Comma separated list of form fields')
                ->schema()
                    ->string()->end()
                ->end()
            ->end()
            ->requestBody()
                ->content()
                    ->schema()
                        ->object()
                            ->property('data', 'object')->ref(sprintf('#/components/schemas/%s', $schemaName))->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->response('201')
                ->description('Resource created')
                ->content()
                    ->schema()
                        ->object()
                            ->property('actions', 'array')->items()->object()->end()->end()->end()
                            ->property('actionsSecondary', 'array')->items()->object()->end()->end()->end()
                            ->property('form', 'object')->end()
                            ->property('metadata', 'object')->end()
                            ->property('resource', 'object')->end()
                            ->property('tabs', 'object')->end()
                            ->property('url', 'string')->end()
                            ->property('redirect', 'string')->end()
        ;
    }

    public static function getName(): ?string
    {
        return 'resource_create';
    }
}
