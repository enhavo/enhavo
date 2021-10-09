<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\FormBundle\Error\FormErrorResolver;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Form\Data\DeleteConfirm;
use Enhavo\Bundle\UserBundle\Form\Data\ResetPassword;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class DeleteController
 * @package Enhavo\Bundle\UserBundle\Controller
 */
class DeleteController extends AbstractUserController
{
    /** @var TranslatorInterface */
    private $translator;

    /** @var FormErrorResolver */
    private $errorResolver;

    /**
     * ResetPasswordController constructor.
     * @param UserManager $userManager
     * @param ConfigurationProvider $provider
     * @param TranslatorInterface $translator
     * @param FormErrorResolver $errorResolver
     */
    public function __construct(UserManager $userManager, ConfigurationProvider $provider, TranslatorInterface $translator, FormErrorResolver $errorResolver)
    {
        parent::__construct($userManager, $provider);

        $this->translator = $translator;
        $this->errorResolver = $errorResolver;
    }

    public function confirmAction(Request $request)
    {
        $configKey = $this->getConfigKey($request);
        $configuration = $this->provider->getDeleteConfirmConfiguration($configKey);

        /** @var UserInterface $user */
        $user = $this->getUser();

        if ($user === null) {
            throw $this->createAccessDeniedException();
        }

        $deleteConfirm = new DeleteConfirm();
        $form = $this->createForm($configuration->getFormClass(), $deleteConfirm, $configuration->getFormOptions());
        $form->handleRequest($request);

        $valid = true;
        $message = null;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user = $this->getUser();

                $message = $this->translator->trans('reset_password.flash.message.success', [], 'EnhavoUserBundle');
                $this->addFlash('success', $message);

                $this->userManager->logout();
                $this->userManager->delete($user, $configuration);

                $url = $this->generateUrl($configuration->getRedirectRoute());

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'error' => false,
                        'errors' => [],
                        'message' => $message,
                        'redirect_url' => $url,
                    ]);
                }

                return new RedirectResponse($url);
            } else {
                $valid = false;

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'error' => true,
                        'errors' => [
                            'fields' => $this->errorResolver->getErrorFieldNames($form),
                            'messages' => $this->errorResolver->getErrorMessages($form),
                        ],
                    ]);
                }
            }
        }

        $response = $this->render($this->getTemplate($configuration->getTemplate()), [
            'form' => $form->createView(),
            'error' => !$valid,
            'errors' => [
                'fields' => $this->errorResolver->getErrorFieldNames($form),
                'messages' => $this->errorResolver->getErrorMessages($form),
            ],
            'data' => [
                'messages' => $this->getFlashMessages(),
            ],
        ]);

        if (!$valid) {
            $response->setStatusCode(400);
        }

        return $response;
    }

    public function finishAction(Request $request)
    {
        $configKey = $this->getConfigKey($request);
        $configuration = $this->provider->getResetPasswordFinishConfiguration($configKey);

        return $this->render($this->getTemplate($configuration->getTemplate()));
    }
}
