<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\FormBundle\Error\FormErrorResolver;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Form\Data\ResetPassword;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ResetPasswordController
 * @package Enhavo\Bundle\UserBundle\Controller
 */
class ResetPasswordController extends AbstractUserController
{
    /** @var UserRepository */
    private $userRepository;

    /** @var FactoryInterface */
    private $userFactory;

    /** @var TranslatorInterface */
    private $translator;

    /** @var FormErrorResolver */
    private $errorResolver;

    /**
     * ResetPasswordController constructor.
     * @param UserManager $userManager
     * @param ConfigurationProvider $provider
     * @param UserRepository $userRepository
     * @param FactoryInterface $userFactory
     * @param TranslatorInterface $translator
     * @param FormErrorResolver $errorResolver
     */
    public function __construct(UserManager $userManager, ConfigurationProvider $provider, UserRepository $userRepository, FactoryInterface $userFactory, TranslatorInterface $translator, FormErrorResolver $errorResolver)
    {
        parent::__construct($userManager, $provider);

        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->translator = $translator;
        $this->errorResolver = $errorResolver;
    }

    public function requestAction(Request $request)
    {
        $configKey = $this->getConfigKey($request);
        $configuration = $this->provider->getResetPasswordRequestConfiguration($configKey);

        $form = $this->createForm($configuration->getFormClass(), null, $configuration->getFormOptions([
            'validation_groups' => ['exists', 'reset-password']
        ]));

        $form->handleRequest($request);

        $valid = true;
        $message = null;
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

    public function checkAction(Request $request)
    {
        $configKey = $this->getConfigKey($request);
        $configuration = $this->provider->getResetPasswordCheckConfiguration($configKey);

        return $this->render($this->getTemplate($configuration->getTemplate()));
    }

    public function confirmAction(Request $request, $token)
    {
        $configKey = $this->getConfigKey($request);
        $configuration = $this->provider->getResetPasswordConfirmConfiguration($configKey);

        $user = $this->userRepository->findByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('A user with confirmation token "%s" does not exist', $token));
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

    public function finishAction(Request $request)
    {
        $configKey = $this->getConfigKey($request);
        $configuration = $this->provider->getResetPasswordFinishConfiguration($configKey);

        return $this->render($this->getTemplate($configuration->getTemplate()));
    }
}
