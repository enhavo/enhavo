<?php

namespace Enhavo\Bundle\UserBundle\Endpoint\Type\ResetPassword;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AbstractFormEndpointType;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResetPasswordConfirmEndpointType extends AbstractFormEndpointType
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

    protected function init($options, Request $request, Data $data, Context $context): void
    {
        $token = $request->get('token');
        $user = $this->userRepository->findByConfirmationToken($token);

        if (null === $user) {
            $data->set('message', $this->translator->trans('reset_password.confirm.invalid_token', [], 'EnhavoUserBundle'));
            $context->stop();
            $context->setStatusCode(404);
            return;
        }

        $context->set('targetUser', $user);
        $data->set('token', $token);
    }

    protected function getForm($options, Request $request, Data $data, Context $context): FormInterface
    {
        $user = $context->get('targetUser');
        $configuration = $this->provider->getResetPasswordConfirmConfiguration();
        return $this->createForm($configuration->getFormClass(), $user, $configuration->getFormOptions());
    }

    protected function handleSuccess($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {
        $user = $form->getData();
        $configuration = $this->provider->getResetPasswordConfirmConfiguration();
        $this->userManager->changePassword($user);
        $data->set('autoLogin', $configuration->isAutoLogin());
        if ($configuration->isAutoLogin()) {
            $this->userManager->login($user);
        }
    }

    protected function handleFailed($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {
        $configuration = $this->provider->getResetPasswordConfirmConfiguration();
        $data->set('autoLogin', $configuration->isAutoLogin());
    }

    protected function getRedirectUrl($options, Request $request, Data $data, Context $context, FormInterface $form): ?string
    {
        $configuration = $this->provider->getResetPasswordConfirmConfiguration();
        return $this->generateUrl($configuration->getRedirectRoute());
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $configuration = $this->provider->getResetPasswordConfirmConfiguration();

        $resolver->setDefaults([
            'template' => $this->resolveTemplate($configuration->getTemplate()),
        ]);
    }
}
