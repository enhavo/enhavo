<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-01-17
 * Time: 11:46
 */

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\AppBundle\Viewer\ViewFactory;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use FOS\RestBundle\View\ViewHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangePasswordController extends AbstractController
{
//    use UserConfigurationTrait;

    /**
     * @var ViewHandler
     */
    private $viewHandler;

    /**
     * @var ViewFactory
     */
    private $viewFactory;

    /**
     * @var UserManager
     */
    private $userManager;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * ChangePasswordController constructor.
     * @param ViewHandler $viewHandler
     * @param ViewFactory $viewFactory
     * @param UserManager $userManager
     * @param TranslatorInterface $translator
     */
    public function __construct(ViewHandler $viewHandler, ViewFactory $viewFactory, UserManager $userManager, TranslatorInterface $translator)
    {
        $this->viewHandler = $viewHandler;
        $this->viewFactory = $viewFactory;
        $this->userManager = $userManager;
        $this->translator = $translator;
    }

    public function changeAction(Request $request)
    {
        $config = $this->userManager->getConfigKey($request);
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->userManager->createForm($config, 'change_password', $user);
        $form->setData($user);
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            if ($form->isValid()) {
                $this->userManager->update($user, false);
                $this->addFlash('success', $this->translator->trans('change_password.message.success', [], 'EnhavoUserBundle'));
            } else {
                $this->addFlash('error', $this->translator->trans('change_password.message.error', [], 'EnhavoUserBundle'));
            }
        }

        $view = $this->viewFactory->create('form', [
            'resource' => $user,
            'form' => $form,
        ]);

        return $this->viewHandler->handle($view);
    }
}
