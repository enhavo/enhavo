<?php

namespace Enhavo\Bundle\UserBundle\Endpoint\Type\ChangePassword;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangePasswordEndpointType extends AbstractFormEndpointType
{
    use TemplateResolverTrait;

    public function __construct(
        private readonly ConfigurationProvider $provider,
        private readonly UserManager $userManager,
        private readonly TranslatorInterface $translator,
    )
    {
    }

    protected function init($options, Request $request, Data $data, Context $context): void
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }
    }

    protected function getForm($options, Request $request, Data $data, Context $context): FormInterface
    {
        $configuration = $this->provider->getChangePasswordConfiguration();
        $user = $this->getUser();
        return $this->createForm($configuration->getFormClass(), $user, $configuration->getFormOptions());
    }

    protected function handleSuccess($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {
        $user = $form->getData();
        $this->userManager->update($user);
        $data->set('message', $this->translator->trans('change_password.message.success', [], 'EnhavoUserBundle'));
    }

    protected function handleFailed($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {
        $data->set('message', $this->translator->trans('change_password.message.error', [], 'EnhavoUserBundle'));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $configuration = $this->provider->getChangePasswordConfiguration();

        $resolver->setDefaults([
            'template' => $this->resolveTemplate($configuration->getTemplate()),
        ]);
    }
}
