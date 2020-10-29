<?php
/**
 * @author blutze-media
 * @since 2020-10-22
 */

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /** @var UserManager */
    private $userManager;

    /** @var RouterInterface */
    private $router;

    /** @var TemplateManager */
    private $templateManager;

    /** @var CsrfTokenManagerInterface */
    private $tokenManager;

    /**
     * SecurityController constructor.
     * @param UserManager $userManager
     * @param RouterInterface $router
     * @param TemplateManager $templateManager
     * @param CsrfTokenManagerInterface $tokenManager
     */
    public function __construct(UserManager $userManager, RouterInterface $router, TemplateManager $templateManager, CsrfTokenManagerInterface $tokenManager)
    {
        $this->userManager = $userManager;
        $this->router = $router;
        $this->templateManager = $templateManager;
        $this->tokenManager = $tokenManager;
    }

    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $config = $request->attributes->get('_config');

        if ($this->isGranted('ROLE_USER')) {
            $route = $this->userManager->getRedirectRoute($config, 'login', null);
            return new RedirectResponse($this->router->generate($route));
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $csrfToken = $this->tokenManager
            ? $this->tokenManager->getToken('authenticate')->getValue()
            : null;

        $template = $this->userManager->getTemplate($config, 'login');

        return $this->render($this->templateManager->getTemplate($template), [
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'stylesheets' => $this->userManager->getStylesheets($config, 'login'),
            'javascripts' => $this->userManager->getJavascripts($config, 'login'),
            'data' => [
                'messages' => [],
            ],
        ]);
    }

    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}
