<?php
/**
 * @author blutze-media
 * @author gseidel
 * @since 2020-10-26
 */

namespace Enhavo\Bundle\UserBundle\Security\Authentication;

use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\Security\Authentication\Badge\CsrfDisableFormProtectionBadge;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiTokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly UserRepository $userRepository,
    )
    {
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('authorization') &&
            str_starts_with($request->headers->get('authorization'), 'Bearer ');
    }

    public function authenticate(Request $request): Passport
    {
        $apiToken = substr($request->headers->get('authorization'), 7); ;
        if (empty($apiToken)) {
            throw new BadCredentialsException('Token not valid');
        }

        $userIdentifier = $this->userRepository->findIdentifierByApToken($apiToken);

        if (null === $userIdentifier) {
            throw new BadCredentialsException('Token not valid');
        }

        return new SelfValidatingPassport(new UserBadge($userIdentifier), [
            new CsrfDisableFormProtectionBadge()
        ]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => 'Token not valid'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
