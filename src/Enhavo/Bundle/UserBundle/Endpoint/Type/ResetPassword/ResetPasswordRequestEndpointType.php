<?php

namespace Enhavo\Bundle\UserBundle\Endpoint\Type\ResetPassword;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AbstractFormEndpointType;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResetPasswordRequestEndpointType extends AbstractFormEndpointType
{
    use TemplateResolverTrait;

    public function __construct(
        private readonly ConfigurationProvider $provider,
        private readonly UserManager $userManager,
        private readonly UserRepository $userRepository,
        private readonly TranslatorInterface $translator,
    )
    {
    }

    protected function getForm($options, Request $request, Data $data, Context $context): FormInterface
    {
        $configuration = $this->provider->getResetPasswordRequestConfiguration();

        return $this->createForm($configuration->getFormClass(), null, $configuration->getFormOptions([
            'validation_groups' => ['exists', 'reset-password']
        ]));
    }

    protected function handleSuccess($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {
        $configuration = $this->provider->getResetPasswordRequestConfiguration();
        $data = $form->getData();
        $user = $this->userRepository->loadUserByIdentifier($data->getUserIdentifier());

        $data->set('message', $this->translator->trans('reset_password.flash.message.success', [], 'EnhavoUserBundle'));
        $this->userManager->resetPassword($user, $configuration);
    }

    protected function getRedirectUrl($options, Request $request, Data $data, Context $context, FormInterface $form): ?string
    {
        $configuration = $this->provider->getResetPasswordRequestConfiguration();
        return $this->generateUrl($configuration->getRedirectRoute());
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $configuration = $this->provider->getResetPasswordRequestConfiguration();

        $resolver->setDefaults([
            'template' => $this->resolveTemplate($configuration->getTemplate()),
        ]);
    }
}
