<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-10
 * Time: 14:57
 */

namespace Enhavo\Bundle\UserBundle\Behat\Controller;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\UserBundle\Configuration\Login\LoginConfiguration;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{
    /** @var UserManager */
    private $userManager;

    /**
     * @var EntityRepository
     */
    private $userRepository;

    /**
     * SecurityController constructor.
     * @param UserManager $userManager
     * @param EntityRepository $userRepository
     */
    public function __construct(UserManager $userManager, EntityRepository $userRepository)
    {
        $this->userManager = $userManager;
        $this->userRepository = $userRepository;
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request): Response
    {
        $username = $request->get('username');

        /** @var UserInterface $user */
        $user = $this->userRepository->findOneBy([
            'username' => $username
        ]);


        $response = new Response('Ok');
        $this->userManager->login($user);
        return $response;
    }
}
