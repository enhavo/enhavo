<?php
/**
 * @author blutze-media
 * @since 2020-10-22
 */

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Security\Authentication\AuthenticationError;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractUserController
{
    public function __construct(
        UserManager $userManager,
        ConfigurationProvider $configurationProvider,
        private CsrfTokenManagerInterface $tokenManager,
        private AuthenticationUtils $authenticationUtils,
        private AuthenticationError $authenticationError,
    ) {
        parent::__construct($userManager, $configurationProvider);
    }

    public function loginAction(Request $request): Response
    {
        $configuration = $this->provider->getLoginConfiguration();

        if ($this->isGranted('ROLE_USER')) {
            $url = $this->generateUrl($configuration->getRedirectRoute());
            return new RedirectResponse($url);
        }

        $error = $this->authenticationError->getError();
        $lastUsername = $this->authenticationUtils->getLastUsername(); // last username entered by the user
        $csrfToken = $this->tokenManager?->getToken('authenticate')->getValue();

        $form = $this->createForm($configuration->getFormClass(), null, $configuration->getFormOptions());

        $template = $this->getTemplate($configuration->getTemplate());
        $response = $this->render($template, [
            'last_username' => $lastUsername,
            'error' => $error,
            'form' => $form->createView(),
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

    public function logoutAction()
    {
        throw new \LogicException('You must activate the logout in your security firewall configuration.');
    }
}
