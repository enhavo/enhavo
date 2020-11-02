<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\UserBundle\Form\Type\ProfileType;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package Enhavo\Bundle\UserBundle\Controller
 */
class UserController extends AbstractController
{
    /** @var UserManager */
    private $userManager;

    /** @var TemplateManager */
    private $templateManager;

    /**
     * UserController constructor.
     * @param UserManager $userManager
     * @param TemplateManager $templateManager
     */
    public function __construct(UserManager $userManager, TemplateManager $templateManager)
    {
        $this->userManager = $userManager;
        $this->templateManager = $templateManager;
    }


    public function profileAction(Request $request)
    {
        /** @var UserInterface $user */
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        $message = null;

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $this->userManager->update($user);
                $message = 'success';

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'error' => false,
                        'errors' => [],
                        'message' => $message,
                    ]);
                }
            } else {
                $message = 'error';

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'error' => true,
                        'errors' => ['error'],
                        'message' => $message,
                    ]);
                }
            }
        }

        return $this->render($this->templateManager->getTemplate('theme/resource/user/profile.html.twig'), [
            'form' => $form->createView(),
            'message' => $message,
        ]);
    }
}
