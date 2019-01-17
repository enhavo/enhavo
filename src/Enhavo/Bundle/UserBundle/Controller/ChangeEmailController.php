<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-01-17
 * Time: 12:26
 */

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\UserBundle\Form\Type\ChangeEmailType;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChangeEmailController extends AbstractController
{
    use UserConfigurationTrait;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var ViewHandler
     */
    private $viewHandler;

    /**
     * ChangeEmailController constructor.
     * @param Session $session
     * @param UserManager $userManager
     * @param ViewHandler $viewHandler
     */
    public function __construct(Session $session, UserManager $userManager, ViewHandler $viewHandler)
    {
        $this->session = $session;
        $this->userManager = $userManager;
        $this->viewHandler = $viewHandler;
    }

    public function checkEmailAction(Request $request)
    {
        $email = $this->session->get('fos_user_send_confirmation_email/email');
        $this->session->remove('fos_user_send_confirmation_email/email');
        $user = $this->userManager->findUserByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }

        $configuration = $this->createConfiguration($request);

        $view = View::create()
            ->setData([
                'user' => $this->getUser(),
            ])
            ->setStatusCode(200)
            ->setTemplate($configuration->getTemplate($configuration->getTemplate('EnhavoUserBundle:Theme/User:checkEmail.html.twig')))
        ;

        return $this->viewHandler->handle($view);
    }

    public function changeEmailAction(Request $request)
    {
        $user = $this->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }

        $configuration = $this->createConfiguration($request);
        $form = $this->createForm($configuration->getForm(ChangeEmailType::class));

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
            ->setTemplate($configuration->getTemplate('EnhavoUserBundle:Admin:User/change-email.html.twig'))
        ;

        return $this->viewHandler->handle($view);
    }
}