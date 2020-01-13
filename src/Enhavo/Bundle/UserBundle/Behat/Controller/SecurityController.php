<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-10
 * Time: 14:57
 */

namespace Enhavo\Bundle\UserBundle\Behat\Controller;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use FOS\UserBundle\Security\LoginManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{
    /**
     * @var LoginManagerInterface
     */
    private $loginManager;

    /**
     * @var EntityRepository
     */
    private $userRepository;

    /**
     * SecurityController constructor.
     * @param LoginManagerInterface $loginManager
     * @param EntityRepository $userRepository
     */
    public function __construct(LoginManagerInterface $loginManager, EntityRepository $userRepository)
    {
        $this->loginManager = $loginManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request)
    {
        $username = $request->get('username');

        /** @var UserInterface $user */
        $user = $this->userRepository->findOneBy([
            'username' => $username
        ]);

        $response = new Response('Ok');
        $this->loginManager->logInUser('main', $user, $response);
        return $response;
    }
}
