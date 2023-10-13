<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\FormBundle\Error\FormErrorResolver;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Form\Data\ChangeEmail;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ChangeEmailController
 * @package Enhavo\Bundle\UserBundle\Controller
 */
class ChangeEmailController extends AbstractUserController
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
        $configuration = $this->provider->getChangeEmailRequestConfiguration();

        /** @var UserInterface $user */
        $user = $this->getUser();

        $form = $this->createForm($configuration->getFormClass(), $user, $configuration->getFormOptions());
        $form->handleRequest($request);

        $valid = true;
        $message = null;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var UserInterface $user */
                $this->userManager->requestChangeEmail($user, $configuration);

                $message = $this->translator->trans('change_email.flash.message.error', [], 'EnhavoUserBundle');
                $this->addFlash('error', $message);

                $url = $this->generateUrl($configuration->getRedirectRoute());

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'error' => true,
                        'errors' => [
                            'fields' => $this->errorResolver->getErrorFieldNames($form),
                            'messages' => $this->errorResolver->getErrorMessages($form),
                        ],
                        'message' => $message,
                        'redirect' => $url
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

        $response = $this->render($this->resolveTemplate($configuration->getTemplate()), [
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
        $configuration = $this->provider->getChangeEmailCheckConfiguration();

        return $this->render($this->resolveTemplate($configuration->getTemplate()));
    }

    public function confirmAction(Request $request, $token)
    {
        $configuration = $this->provider->getChangeEmailConfirmConfiguration();

        $user = $this->userRepository->findByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('A user with confirmation token "%s" does not exist', $token));
        }

        $changeEmail = new ChangeEmail();
        $form = $this->createForm($configuration->getFormClass(), $changeEmail, $configuration->getFormOptions());
        $form->handleRequest($request);

        $valid = true;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->userManager->changeEmail($user, $changeEmail->getEmail(), $configuration);
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

        $response = $this->render($this->resolveTemplate($configuration->getTemplate()), [
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
        $configuration = $this->provider->getChangeEmailFinishConfiguration();

        return $this->render($this->resolveTemplate($configuration->getTemplate()));
    }
}
