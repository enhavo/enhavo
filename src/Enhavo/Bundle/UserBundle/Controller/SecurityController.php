<?php
/**
 * @author blutze-media
 * @since 2020-10-22
 */

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    /** @var UserManager */
    private $userManager;

    /** @var FactoryInterface */
    private $userFactory;

    /** @var TemplateManager */
    private $templateManager;

    /**
     * SecurityController constructor.
     * @param UserManager $userManager
     * @param FactoryInterface $userFactory
     * @param TemplateManager $templateManager
     */
    public function __construct(UserManager $userManager, FactoryInterface $userFactory, TemplateManager $templateManager)
    {
        $this->userManager = $userManager;
        $this->userFactory = $userFactory;
        $this->templateManager = $templateManager;
    }

    public function registerAction(Request $request)
    {
        $config = $request->attributes->get('_config');
        /** @var UserInterface $user */
        $user = $this->userFactory->createNew();
        $form = $this->userManager->createForm($config, $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->userManager->register($user);

            $template = $this->templateManager->getTemplate($this->userManager->getTemplate($config));
            return $this->render($template, [
                'user' => $user
            ]);
        }
    }
}
