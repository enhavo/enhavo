<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-01-17
 * Time: 12:29
 */

namespace Enhavo\Bundle\UserBundle\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\UserBundle\Form\Type\ProfileType;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends AbstractController
{
    use UserConfigurationTrait;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ViewHandler
     */
    private $viewHandler;

    /**
     * ProfileController constructor.
     * @param EntityManagerInterface $em
     * @param ViewHandler $viewHandler
     */
    public function __construct(EntityManagerInterface $em, ViewHandler $viewHandler)
    {
        $this->em = $em;
        $this->viewHandler = $viewHandler;
    }

    public function profileAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }

        $configuration = $this->createConfiguration($request);

        $form = $this->createForm($configuration->getForm(ProfileType::class), $user);

        $valid = true;
        $form->handleRequest($request);
        if (in_array($request->getMethod(), ['POST'])) {
            if($form->isValid()) {
                $this->em->flush();
            } else {
                $valid = false;
            }
        }

        $view = View::create($form)
            ->setData([
                'form' => $form->createView(),
            ])
            ->setStatusCode($valid ? 200 : 400)
            ->setTemplate($configuration->getTemplate('EnhavoUserBundle:Theme:User/profile.html.twig'))
        ;

        return $this->viewHandler->handle($view);
    }
}