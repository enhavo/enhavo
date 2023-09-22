<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\FormBundle\Error\FormErrorResolver;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController
 * @package Enhavo\Bundle\UserBundle\Controller
 */
class ProfileController extends AbstractUserController
{
    /** @var TranslatorInterface */
    private $translator;

    /** @var FormErrorResolver */
    private $errorResolver;

    /**
     * UserController constructor.
     * @param UserManager $userManager
     * @param ConfigurationProvider $configurationProvider
     * @param TranslatorInterface $translator
     * @param FormErrorResolver $errorResolver
     */
    public function __construct(UserManager $userManager, ConfigurationProvider $configurationProvider, TranslatorInterface $translator, FormErrorResolver $errorResolver)
    {
        parent::__construct($userManager, $configurationProvider);

        $this->translator = $translator;
        $this->errorResolver = $errorResolver;
    }

    public function indexAction(Request $request)
    {
        $configuration = $this->provider->getProfileConfiguration();

        /** @var UserInterface $user */
        $user = $this->getUser();
        $form = $this->createForm($configuration->getFormClass(), $user, $configuration->getFormOptions());
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

        return $this->render($this->resolveTemplate($configuration->getTemplate()), [
            'form' => $form->createView(),
            'data' => [
                'messages' => $this->getFlashMessages()
            ],
        ]);
    }
}
