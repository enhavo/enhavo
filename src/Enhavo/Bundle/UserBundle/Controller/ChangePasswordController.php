<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-01-17
 * Time: 11:46
 */

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangePasswordController extends AbstractUserController
{
    private FactoryInterface $viewFactory;
    private TranslatorInterface $translator;

    /**
     * @param UserManager $userManager
     * @param ConfigurationProvider $provider
     * @param FactoryInterface $viewFactory
     * @param TranslatorInterface $translator
     */
    public function __construct(UserManager $userManager, ConfigurationProvider $provider, FactoryInterface $viewFactory, TranslatorInterface $translator)
    {
        parent::__construct($userManager, $provider);

        $this->viewFactory = $viewFactory;
        $this->translator = $translator;
    }

    public function changeAction(Request $request)
    {
        $configKey = $this->getConfigKey($request);
        $configuration = $this->provider->getChangePasswordConfiguration($configKey);

        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->createForm($configuration->getFormClass(), $user, $configuration->getFormOptions());
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            if ($form->isValid()) {
                $this->userManager->update($user);
                $this->addFlash('success', $this->translator->trans('change_password.message.success', [], 'EnhavoUserBundle'));
            } else {
                $this->addFlash('error', $this->translator->trans('change_password.message.error', [], 'EnhavoUserBundle'));
            }
        }

        $view = $this->viewFactory->create([
            'type' => 'form',
            'resource' => $user,
            'manager' => $this->userManager,
            'form' => $form,
            'template' => $this->getTemplate($configuration->getTemplate()),
            'request' => $request,
        ]);

        return $view->getResponse($request);
    }
}
