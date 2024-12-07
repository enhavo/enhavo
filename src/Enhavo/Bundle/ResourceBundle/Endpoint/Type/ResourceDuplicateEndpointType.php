<?php

namespace Enhavo\Bundle\ResourceBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Authorization\Permission;
use Enhavo\Bundle\ResourceBundle\Input\Input;
use Enhavo\Bundle\ResourceBundle\Input\InputFactory;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ResourceDuplicateEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly InputFactory $inputFactory,
        private readonly ResourceManager $resourceManager,
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
        private readonly RouteResolverInterface $routeResolver,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var Input $input */
        $input = $this->inputFactory->create($options['input']);

        $resource = $input->getResource();

        if ($resource === null) {
            throw $this->createNotFoundException();
        }

        $this->denyAccessUnlessGranted(new Permission($input->getResourceName(), $options['permission']), $resource);

        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('resource_duplicate', $request->getPayload()->get('token')))) {
            $context->setStatusCode(400);
            $data['success'] = false;
            $data['message'] = 'Invalid token';
            return;
        }

        $duplicateResource = $this->resourceManager->duplicate($resource);
        $this->resourceManager->save($duplicateResource);

        $redirectRoute = $this->routeResolver->getRoute('update', ['api' => false]) ?? $options['update_route'];
        $apiRoute = $this->routeResolver->getRoute('update', ['api' => true])  ?? $options['update_api_route'];

        $data->set('url', $this->generateUrl($apiRoute, ['id' => $duplicateResource->getId()]));
        if ($redirectRoute) {
            $data->set('redirect', $this->generateUrl($redirectRoute, ['id' => $duplicateResource->getId()]));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'update_route' => null,
            'update_api_route' => null,
            'permission' => Permission::UPDATE,
        ]);

        $resolver->setRequired('input');
    }

    public static function getName(): ?string
    {
        return 'resource_duplicate';
    }
}
