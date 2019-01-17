<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-01-17
 * Time: 11:46
 */

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class ChangePasswordController extends AbstractController
{
    use UserConfigurationTrait;

    /**
     * @var ViewHandler
     */
    private $viewHandler;

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
     * @param UserManager $userManager
     * @param FormFactory $formFactory
     */
    public function __construct(ViewHandler $viewHandler, UserManager $userManager, FormFactory $formFactory)
    {
        $this->viewHandler = $viewHandler;
        $this->userManager = $userManager;
        $this->formFactory = $formFactory;
    }

    public function changePasswordAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }

        $configuration = $this->createConfiguration($request);

        $form = $this->formFactory->createForm();
        $form->setData($user);
        $form->handleRequest($request);
        $valid = true;
        if($request->getMethod() == 'POST') {
            if ($form->isValid()) {
                $this->userManager->updateUser($user);
            } else {
                $valid = false;
            }
        }

        $view = View::create($form)
            ->setData([
                'form' => $form->createView(),
            ])
            ->setStatusCode($valid ? 200 : 400)
            ->setTemplate($configuration->getTemplate('EnhavoUserBundle:Admin:User/change-password.html.twig'))
        ;

        return $this->viewHandler->handle($view);
    }
}