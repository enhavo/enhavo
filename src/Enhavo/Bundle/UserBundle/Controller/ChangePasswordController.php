<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-01-17
 * Time: 11:46
 */

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\AppBundle\Viewer\ViewFactory;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use FOS\RestBundle\View\ViewHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangePasswordController extends AbstractUserController
{
    /** @var ViewHandler*/
    private $viewHandler;

    /** @var ViewFactory */
    private $viewFactory;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * ChangePasswordController constructor.
     * @param UserManager $userManager
     * @param ConfigurationProvider $provider
     * @param ViewHandler $viewHandler
     * @param ViewFactory $viewFactory
     * @param TranslatorInterface $translator
     */
    public function __construct(UserManager $userManager, ConfigurationProvider $provider, ViewHandler $viewHandler, ViewFactory $viewFactory, TranslatorInterface $translator)
    {
        parent::__construct($userManager, $provider);

        $this->viewHandler = $viewHandler;
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

        $view = $this->viewFactory->create('form', [
            'resource' => $user,
            'form' => $form,
            'template' => $configuration->getTemplate()
        ]);

        return $this->viewHandler->handle($view);
    }
}
