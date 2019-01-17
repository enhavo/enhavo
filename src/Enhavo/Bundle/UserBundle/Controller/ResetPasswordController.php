<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-01-17
 * Time: 11:46
 */

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\UserBundle\User\UserManager;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ResetPasswordController extends AbstractController
{
    use UserConfigurationTrait;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var FactoryInterface
     */
    private $formFactory;

    /**
     * ResetPasswordController constructor.
     *
     * @param UserManager $userManager
     * @param FactoryInterface $formFactory
     */
    public function __construct(UserManager $userManager, FactoryInterface $formFactory)
    {
        $this->userManager = $userManager;
        $this->formFactory = $formFactory;
    }

    public function resetPasswordAction(Request $request)
    {
        $configuration = $this->createConfiguration($request);

        $error = false;
        $reset = false;
        if($request->getMethod() == 'POST') {
            $user = $this->userManager->findUserByUsernameOrEmail($request->get('username'));
            if($user === null) {
                $error = true;
            } else {
                $reset = true;
                $template = null;
                $this->userManager->sendResetEmail($user, $configuration->getMailTemplate(), $configuration->getConfirmRoute());
            }
        }

        return $this->render($configuration->getTemplate('EnhavoUserBundle:Admin:User/reset-password.html.twig'), array(
            'error' => $error,
            'reset' => $reset,
        ));
    }

    public function resetPasswordConfirmAction(Request $request)
    {
        $configuration = $this->createConfiguration($request);

        $token = $request->get('token');
        $user = $this->userManager->findUserByConfirmationToken($token);
        $form = $this->formFactory->createForm();
        $form->setData($user);
        $error = null;

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $user->setConfirmationToken(null);
                $user->setPasswordRequestedAt(null);
                $this->userManager->updateUser($user);
                $url = $this->generateUrl($configuration->getRedirectRoute());

                $response = new RedirectResponse($url);
                $this->userManager->authenticateUser($user, $response);
                return $response;
            } else {
                foreach($form->getErrors() as $errorMessage) {
                    $error = $errorMessage;
                }
            }
        }

        return $this->render($configuration->getTemplate('EnhavoUserBundle:Admin:User/change-password.html.twig'), array(
            'token' => $token,
            'error' => $error,
            'form' => $form->createView(),
        ));
    }
}