<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\FormBundle\Error\FormErrorResolver;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Exception\ConfigurationException;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RegistrationController
 * @package Enhavo\Bundle\UserBundle\Controller
 */
class RegistrationController extends AbstractUserController
{
    private UserRepository $userRepository;
    private FactoryInterface $userFactory;
    private FormErrorResolver $errorResolver;

    /**
     * RegistrationController constructor.
     * @param UserManager $userManager
     * @param ConfigurationProvider $provider
     * @param UserRepository $userRepository
     * @param FactoryInterface $userFactory
     * @param FormErrorResolver $errorResolver
     */
    public function __construct(UserManager $userManager, ConfigurationProvider $provider, UserRepository $userRepository, FactoryInterface $userFactory, FormErrorResolver $errorResolver)
    {
        parent::__construct($userManager, $provider);

        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->errorResolver = $errorResolver;
    }

    /**
     * @throws ConfigurationException
     */
    public function registerAction(Request $request): RedirectResponse|JsonResponse|Response
    {
        $configuration = $this->provider->getRegistrationRegisterConfiguration();

        /** @var UserInterface $user */
        $user = $this->userFactory->createNew();

        $form = $this->createForm($configuration->getFormClass(), $user, $configuration->getFormOptions([
            'validation_groups' => ['register']
        ]));

        $form->handleRequest($request);

        $valid = true;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->userManager->register($user, $configuration);

                if ($configuration->isAutoLogin()) {
                    $this->userManager->login($user);
                }

                $url = $this->generateUrl($configuration->getRedirectRoute());

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'error' => false,
                        'errors' => [],
                        'redirect_url' => $url
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
        $configuration = $this->provider->getRegistrationCheckConfiguration();

        return $this->render($this->getTemplate($configuration->getTemplate()));
    }

    /**
     * @throws ConfigurationException
     */
    public function confirmAction(Request $request, $token): RedirectResponse
    {
        $configuration = $this->provider->getRegistrationConfirmConfiguration();

        $user = $this->userRepository->findByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('A user with confirmation token "%s" does not exist', $token));
        }

        $this->userManager->confirm($user, $configuration);
        if ($configuration->isAutoLogin()) {
            $this->userManager->login($user);
        }

        $url = $this->generateUrl($configuration->getRedirectRoute());

        return new RedirectResponse($url);
    }

    /**
     * @throws ConfigurationException
     */
    public function finishAction(Request $request): Response
    {
        $configuration = $this->provider->getRegistrationFinishConfiguration();

        return $this->render($this->getTemplate($configuration->getTemplate()));
    }
}
