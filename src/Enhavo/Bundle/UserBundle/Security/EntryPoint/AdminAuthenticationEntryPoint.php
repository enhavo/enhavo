<?php

namespace Enhavo\Bundle\UserBundle\Security\EntryPoint;

use Enhavo\Bundle\ApiBundle\Endpoint\Endpoint;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class AdminAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private readonly FormAuthenticationEntryPoint $formAuthenticationEntryPoint,
        private readonly FactoryInterface $factory,
        private readonly string $apiAuthenticationRegex,
        private readonly string $formAuthenticationRegex,
    )
    {
    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        $path = $request->getPathInfo();
        if (preg_match($this->apiAuthenticationRegex, $path)) {
            return new JsonResponse('Not Authenticated', 401);
        } else if (preg_match($this->formAuthenticationRegex, $path)) {
            return $this->formAuthenticationEntryPoint->start($request, $authException);
        }

        /** @var Endpoint $endpoint */
        $endpoint = $this->factory->create([
            'type' => 'admin',
            'component' => 'user-unauthenticated',
        ]);

        $response = $endpoint->getResponse($request);
        $response->setStatusCode(401);
        return $response;
    }
}
