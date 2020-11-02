<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RegistrationController
 * @package Enhavo\Bundle\UserBundle\Controller
 *
 * @property $userFactory UserFactory
 */
class RegistrationController extends AbstractController
{
    /** @var UserManager */
    private $userManager;

    /** @var UserRepository */
    private $userRepository;

    /** @var TemplateManager */
    private $templateManager;

    /** @var FactoryInterface */
    private $userFactory;

    /**
     * RegistrationController constructor.
     * @param UserManager $userManager
     * @param UserRepository $userRepository
     * @param TemplateManager $templateManager
     * @param FactoryInterface $userFactory
     */
    public function __construct(UserManager $userManager, UserRepository $userRepository, TemplateManager $templateManager, FactoryInterface $userFactory)
    {
        $this->userManager = $userManager;
        $this->userRepository = $userRepository;
        $this->templateManager = $templateManager;
        $this->userFactory = $userFactory;
    }


    public function registerAction(Request $request)
    {
        $config = $request->attributes->get('_config');

        /** @var UserInterface $user */
        $user = $this->userFactory->createNew();

        $form = $this->userManager->createForm($config, 'registration_register', $user, [
            'validation_groups' => ['register']
        ]);
        $form->handleRequest($request);

        $valid = true;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->userManager->register($user, $config, 'registration_register');
                $url = $this->generateUrl($this->userManager->getRedirectRoute($config, 'registration_register'));

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
            }
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'error' => !$valid,
                'errors' => [], // todo: insert errors from enhavo form-error-resolver
            ]);
        }

        return $this->render($this->getTemplate($this->userManager->getTemplate($config, 'registration_register')), [
            'form' => $form->createView(),
        ])->setStatusCode($valid ? 200 : 400);
    }

    public function checkAction(Request $request)
    {
        $config = $request->attributes->get('_config');
        return $this->render($this->getTemplate($this->userManager->getTemplate($config, 'registration_check')));
    }

    public function confirmAction(Request $request, $token)
    {
        $config = $request->attributes->get('_config');
        $user = $this->userRepository->findByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('A user with confirmation token "%s" does not exist', $token));
        }

        $this->userManager->confirm($user, $config, 'registration_confirm');

        $url = $this->generateUrl($this->userManager->getRedirectRoute($config, 'registration_confirm'));

        return new RedirectResponse($url);
    }

    public function finishAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $config = $request->attributes->get('_config');

        return $this->render($this->getTemplate($this->userManager->getTemplate($config, 'registration_finish')), [

        ]);
    }

    private function getTemplate($template)
    {
        return $this->templateManager->getTemplate($template);
    }
}
