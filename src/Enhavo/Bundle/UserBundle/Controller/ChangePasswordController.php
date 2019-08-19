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
use FOS\UserBundle\Form\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ChangePasswordController extends AbstractController
{
    use UserConfigurationTrait;

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

    /**
     * @var FactoryInterface
     */
    private $formFactory;

    /**
     * ChangePasswordController constructor.
     * @param ViewHandler $viewHandler
     * @param ViewFactory $viewFactory
     * @param UserManager $userManager
     * @param FactoryInterface $formFactory
     */
    public function __construct(ViewHandler $viewHandler, ViewFactory $viewFactory, UserManager $userManager, FactoryInterface $formFactory)
    {
        $this->viewHandler = $viewHandler;
        $this->viewFactory = $viewFactory;
        $this->userManager = $userManager;
        $this->formFactory = $formFactory;
    }

    public function changePasswordAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->formFactory->createForm();
        $form->setData($user);
        $form->handleRequest($request);
        if($request->getMethod() == 'POST') {
            if ($form->isValid()) {
                $this->userManager->updateUser($user);
                $this->addFlash('success', $this->get('translator')->trans('change_password.message.success', [], 'EnhavoUserBundle'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('change_password.message.error', [], 'EnhavoUserBundle'));
            }
        }

        $view = $this->viewFactory->create('form', [
            'resource' => $user,
            'form' => $form
        ]);

        return $this->viewHandler->handle($view);
    }
}
