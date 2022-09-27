<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\FormBundle\Error\FormErrorResolver;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Exception\ConfigurationException;
use Enhavo\Bundle\UserBundle\Exception\TokenInvalidException;
use Enhavo\Bundle\UserBundle\Form\Data\ResetPassword;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ResetPasswordController
 * @package Enhavo\Bundle\UserBundle\Controller
 */
class ResetPasswordController extends AbstractUserController
{
    private UserRepository $userRepository;
    private TranslatorInterface $translator;
    private FormErrorResolver $errorResolver;

    /**
     * ResetPasswordController constructor.
     * @param UserManager $userManager
     * @param ConfigurationProvider $provider
     * @param UserRepository $userRepository
     * @param TranslatorInterface $translator
     * @param FormErrorResolver $errorResolver
     */
    public function __construct(UserManager $userManager, ConfigurationProvider $provider, UserRepository $userRepository, TranslatorInterface $translator, FormErrorResolver $errorResolver)
    {
        parent::__construct($userManager, $provider);

        $this->userRepository = $userRepository;
        $this->translator = $translator;
        $this->errorResolver = $errorResolver;
    }

    /**
     * @throws ConfigurationException
     * @throws Exception
     */
    public function requestAction(Request $request): RedirectResponse|JsonResponse|Response
    {
        $configuration = $this->provider->getResetPasswordRequestConfiguration();

        $form = $this->createForm($configuration->getFormClass(), null, $configuration->getFormOptions([
            'validation_groups' => ['exists', 'reset-password']
        ]));

        $form->handleRequest($request);

        $valid = true;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                /** @var ResetPassword $data */
                $data = $form->getData();
                $user = $this->userRepository->loadUserByIdentifier($data->getUsername());

                $message = $this->translator->trans('reset_password.flash.message.success', [], 'EnhavoUserBundle');
                $this->addFlash('success', $message);

                $this->userManager->resetPassword($user, $configuration);

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

    /**
     * @throws ConfigurationException
     */
    public function checkAction(Request $request): Response
    {
        $configuration = $this->provider->getResetPasswordCheckConfiguration();

        return $this->render($this->getTemplate($configuration->getTemplate()));
    }

    /**
     * @throws ConfigurationException
     */
    public function confirmAction(Request $request, $token): RedirectResponse|JsonResponse|Response
    {
        $configuration = $this->provider->getResetPasswordConfirmConfiguration();

        $user = $this->userRepository->findByConfirmationToken($token);

        if (null === $user) {
            throw new TokenInvalidException(sprintf('A user with confirmation token "%s" does not exist', $token));
        }

        $form = $this->createForm($configuration->getFormClass(), $user, $configuration->getFormOptions());
        $form->handleRequest($request);

        $valid = true;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->userManager->changePassword($user);
                if ($configuration->isAutoLogin()) {
                    $this->userManager->login($user);
                }

                $url = $this->generateUrl($configuration->getRedirectRoute());

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'error' => false,
                        'errors' => [],
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
            'user' => $user,
            'form' => $form->createView(),
            'error' => !$valid,
            'errors' => [
                'fields' => $this->errorResolver->getErrorFieldNames($form),
                'messages' => $this->errorResolver->getErrorMessages($form),
            ],
            'token' => $token,
            'data' => [
                'messages' => $this->getFlashMessages(),
            ]
        ]);

        if (!$valid) {
            $response->setStatusCode(400);
        }

        return $response;
    }

    /**
     * @throws ConfigurationException
     */
    public function finishAction(Request $request): Response
    {
        $configuration = $this->provider->getResetPasswordFinishConfiguration();

        return $this->render($this->getTemplate($configuration->getTemplate()));
    }
}
