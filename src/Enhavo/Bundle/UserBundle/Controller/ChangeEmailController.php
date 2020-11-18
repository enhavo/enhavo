<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\FormBundle\Error\FormErrorResolver;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ChangeEmailController
 * @package Enhavo\Bundle\UserBundle\Controller
 */
class ChangeEmailController extends AbstractController
{
    use FlashMessagesTrait;

    /** @var UserManager */
    private $userManager;

    /** @var UserRepository */
    private $userRepository;

    /** @var TemplateManager */
    private $templateManager;

    /** @var FactoryInterface */
    private $userFactory;

    /** @var TranslatorInterface */
    private $translator;

    /** @var FormErrorResolver */
    private $errorResolver;

    /**
     * ResetPasswordController constructor.
     * @param UserManager $userManager
     * @param UserRepository $userRepository
     * @param TemplateManager $templateManager
     * @param FactoryInterface $userFactory
     * @param TranslatorInterface $translator
     * @param FormErrorResolver $errorResolver
     */
    public function __construct(UserManager $userManager, UserRepository $userRepository, TemplateManager $templateManager, FactoryInterface $userFactory, TranslatorInterface $translator, FormErrorResolver $errorResolver)
    {
        $this->userManager = $userManager;
        $this->userRepository = $userRepository;
        $this->templateManager = $templateManager;
        $this->userFactory = $userFactory;
        $this->translator = $translator;
        $this->errorResolver = $errorResolver;
    }

    public function requestAction(Request $request)
    {
        $config = $this->userManager->getConfigKey($request);
        $action = 'change_email_request';

        /** @var UserInterface $user */

        $form = $this->userManager->createForm($config, $action);
        $form->handleRequest($request);

        $valid = true;
        $message = null;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var UserInterface $currentUser */
                $currentUser = $this->getUser();
                $newEmail = $form->get('email')->getData();
                $success = $this->userManager->changeEmail($currentUser, $newEmail, $config, $action);

                if (false === $success) {
                    $message = $this->translator->trans('change_email.flash.message.error', [], 'EnhavoUserBundle');
                    $this->addFlash('error', $message);

                    if ($request->isXmlHttpRequest()) {
                        return new JsonResponse([
                            'error' => true,
                            'errors' => [
                                'fields' => $this->errorResolver->getErrorFieldNames($form),
                                'messages' => $this->errorResolver->getErrorMessages($form),
                            ],
                            'message' => $message,
                        ]);
                    }
                } else {

                    $this->userManager->login($currentUser);

                    $message = $this->translator->trans('change_email.flash.message.success', [], 'EnhavoUserBundle');
                    $this->addFlash('success', $message);

                    $route = $this->userManager->getRedirectRoute($config, $action);
                    if ($route) {
                        $url = $this->generateUrl($route);

                        if ($request->isXmlHttpRequest()) {
                            return new JsonResponse([
                                'error' => false,
                                'errors' => [],
                                'message' => $message,
                                'redirect_url' => $url,
                            ]);
                        }

                        return new RedirectResponse($url);
                    }
                }


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

        return $this->render($this->getTemplate($this->userManager->getTemplate($config, $action)), [
            'form' => $form->createView(),
            'error' => !$valid,
            'errors' => [
                'fields' => $this->errorResolver->getErrorFieldNames($form),
                'messages' => $this->errorResolver->getErrorMessages($form),
            ],
            'stylesheets' => $this->userManager->getStylesheets($config, $action),
            'javascripts' => $this->userManager->getJavascripts($config, $action),
            'data' => [
                'messages' => $this->getFlashMessages(),
            ],
        ])->setStatusCode($valid ? 200 : 400);
    }

    public function checkAction(Request $request)
    {
        $config = $this->userManager->getConfigKey($request);
        return $this->render($this->getTemplate($this->userManager->getTemplate($config, 'change_email_check')));
    }

    private function getTemplate($template)
    {
        return $this->templateManager->getTemplate($template);
    }
}
