<?php
/**
 * @author blutze-media
 * @since 2020-10-22
 */

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractUserController
{
    /** @var CsrfTokenManagerInterface */
    private $tokenManager;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * SecurityController constructor.
     * @param UserManager $userManager
     * @param ConfigurationProvider $configurationProvider
     * @param CsrfTokenManagerInterface $tokenManager
     * @param TranslatorInterface $translator
     */
    public function __construct(UserManager $userManager, ConfigurationProvider $configurationProvider, CsrfTokenManagerInterface $tokenManager, TranslatorInterface $translator)
    {
        parent::__construct($userManager, $configurationProvider);

        $this->tokenManager = $tokenManager;
        $this->translator = $translator;
    }

    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $configKey = $this->getConfigKey($request);
        $configuration = $this->provider->getLoginConfiguration($configKey);

        if ($this->isGranted('ROLE_USER')) {
            $url = $this->generateUrl($configuration->getRedirectRoute());
            return new RedirectResponse($url);
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            $this->addFlash('error', $this->translator->trans('login.error.credentials', [], 'EnhavoUserBundle'));
        }
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $csrfToken = $this->tokenManager
            ? $this->tokenManager->getToken('authenticate')->getValue()
            : null;

        $template = $this->getTemplate($configuration->getTemplate());
        $response = $this->render($template, [
            'last_username' => $lastUsername,
            'error' => $error,
            'redirect_uri' => $request->query->get('redirect'),
            'csrf_token' => $csrfToken,
            'data' => [
                'view_id' => $request->getSession()->get('enhavo.view_id'),
                'messages' => $this->getFlashMessages(),
                'base_url' => '',
                'host' => $request->getHost(),
                'scheme' => $request->getScheme(),
            ],
        ]);

        if ($error !== null) {
            $response->setStatusCode(400);
        }

        return $response;
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
