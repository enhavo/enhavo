<?php
/**
 * @author blutze-media
 * @since 2020-10-26
 */

namespace Enhavo\Bundle\UserBundle\Security\Authentication;


use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Event\UserLoginEvent;
use Enhavo\Bundle\UserBundle\Exception\ConfigurationException;
use Enhavo\Bundle\UserBundle\Mapper\UserMapperInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class FormLoginAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    /** @var UserManager */
    private $userManager;

    /** @var UrlGeneratorInterface  */
    private $urlGenerator;

    /** @var CsrfTokenManagerInterface  */
    private $csrfTokenManager;

    /** @var UserPasswordEncoderInterface  */
    private $passwordEncoder;

    /** @var UserMapperInterface */
    private $userMapper;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var string */
    private $className;

    /** @var string */
    private $loginRoute;

    /** @var ConfigurationProvider  */
    private $configurationProvider;

    /**
     * FormLoginAuthenticator constructor.
     * @param UserManager $userManager
     * @param ConfigurationProvider $configurationProvider
     * @param UrlGeneratorInterface $urlGenerator
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserMapperInterface $userMapper
     * @param EventDispatcherInterface $eventDispatcher
     * @param string $className
     */
    public function __construct(UserManager $userManager, ConfigurationProvider $configurationProvider, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder, UserMapperInterface $userMapper, EventDispatcherInterface $eventDispatcher, string $className)
    {
        $this->userManager = $userManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->userMapper = $userMapper;
        $this->eventDispatcher = $eventDispatcher;
        $this->className = $className;
        $this->configurationProvider = $configurationProvider;
    }


    public function supports(Request $request)
    {
        $configKey = $this->getConfigKey($request);
        $this->updateLoginRoute($configKey);
        $isRoute = $this->loginRoute === $request->attributes->get('_route');
        $isPost = $request->isMethod('POST');

        return $isRoute && $isPost;
    }

    public function getCredentials(Request $request)
    {
        $properties = $this->userMapper->getCredentialProperties();

        $credentials = [
            'password' => $request->request->get('_password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];

        foreach ($properties as $property) {
            $credentials[$property] = $request->request->get('_' .$property);
        }

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $this->userMapper->getUsername($credentials)
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $username = $this->userMapper->getUsername($credentials);
        $user = $userProvider->loadUserByUsername($username);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Username could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $this->dispatch($token);
        $session = $request->getSession();
        $targetPath = $request->get('_target_path') ?? $this->getTargetPath($session, $providerKey);
        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        $configKey = $this->getConfigKey($request);
        $this->updateLoginRoute($configKey);
        $url = $this->getLoginUrl();

        return new RedirectResponse($url);
    }

    private function dispatch(TokenInterface $token)
    {
        /** @var \Enhavo\Bundle\UserBundle\Model\UserInterface $user */
        $user = $token->getUser();
        $this->eventDispatcher->dispatch(new UserLoginEvent($user));
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $configKey = $this->getConfigKey($request);
        $this->updateLoginRoute($configKey);
        $url = $this->getLoginUrl();

        return new RedirectResponse($url);
    }

    protected function updateLoginRoute($config)
    {
        if (is_string($config)) {
            try {
                $this->loginRoute = $this->configurationProvider->getLoginConfiguration($config)->getRoute();
            } catch (ConfigurationException $e) {
                // don't update login route
            }
        }
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate($this->loginRoute);
    }

    protected function getConfigKey(Request $request)
    {
        $key = $request->attributes->get('_config');
        return $key;
    }
}
