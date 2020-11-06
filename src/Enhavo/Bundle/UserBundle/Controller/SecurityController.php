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
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    use FlashMessagesTrait;

    /** @var UserManager */
    private $userManager;

    /** @var TemplateManager */
    private $templateManager;

    /** @var CsrfTokenManagerInterface */
    private $tokenManager;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * SecurityController constructor.
     * @param UserManager $userManager
     * @param TemplateManager $templateManager
     * @param CsrfTokenManagerInterface $tokenManager
     * @param TranslatorInterface $translator
     */
    public function __construct(UserManager $userManager, TemplateManager $templateManager, CsrfTokenManagerInterface $tokenManager, TranslatorInterface $translator)
    {
        $this->userManager = $userManager;
        $this->templateManager = $templateManager;
        $this->tokenManager = $tokenManager;
        $this->translator = $translator;
    }

    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $config = $request->attributes->get('_config');

        if ($this->isGranted('ROLE_USER')) {
            $url = $this->generateUrl($this->userManager->getRedirectRoute($config, 'login', null));
            return new RedirectResponse($url);
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            $this->addFlash('error', $this->translator->trans('login.error.credentials', [], 'EnhavoUserBundle'));
        }
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
                'messages' => $this->getFlashMessages(),
                'base_url' => '',
                'host' => $request->getHost(),
                'scheme' => $request->getScheme(),
            ],
        ]);
    }

    public function checkAction()
    {
        throw new \LogicException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutAction()
    {
        throw new \LogicException('You must activate the logout in your security firewall configuration.');
    }
}
