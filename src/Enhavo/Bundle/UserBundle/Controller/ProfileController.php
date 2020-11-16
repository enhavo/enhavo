<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\FormBundle\Error\FormErrorResolver;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController
 * @package Enhavo\Bundle\UserBundle\Controller
 */
class ProfileController extends AbstractController
{
    use FlashMessagesTrait;

    /** @var UserManager */
    private $userManager;

    /** @var TemplateManager */
    private $templateManager;

    /** @var TranslatorInterface */
    private $translator;

    /** @var FormErrorResolver */
    private $errorResolver;

    /**
     * UserController constructor.
     * @param UserManager $userManager
     * @param TemplateManager $templateManager
     * @param TranslatorInterface $translator
     * @param FormErrorResolver $errorResolver
     */
    public function __construct(UserManager $userManager, TemplateManager $templateManager, TranslatorInterface $translator, FormErrorResolver $errorResolver)
    {
        $this->userManager = $userManager;
        $this->templateManager = $templateManager;
        $this->translator = $translator;
        $this->errorResolver = $errorResolver;
    }

    public function indexAction(Request $request)
    {
        $config = $request->attributes->get('_config');

        /** @var UserInterface $user */
        $user = $this->getUser();
        $form = $this->userManager->createForm($config, 'profile', $user);
        $form->handleRequest($request);

        $message = null;

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $this->userManager->update($user);
                $message = $this->translator->trans('profile.update.success', [], 'EnhavoUserBundle');
                $this->addFlash('success', $message);

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'error' => false,
                        'errors' => [],
                        'message' => $message,
                    ]);
                }
            } else {
                $message = $this->translator->trans('profile.update.error', [], 'EnhavoUserBundle');
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
            }
        }

        return $this->render($this->templateManager->getTemplate($this->userManager->getTemplate($config, 'profile')), [
            'form' => $form->createView(),
            'data' => [
                'messages' => $this->getFlashMessages()
            ],
        ]);
    }
}
