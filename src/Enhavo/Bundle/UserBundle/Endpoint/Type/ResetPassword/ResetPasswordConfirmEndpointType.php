<?php

namespace Enhavo\Bundle\UserBundle\Endpoint\Type\ResetPassword;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Endpoint\Type\ChangePassword\AbstractFormEndpointType;
use Enhavo\Bundle\UserBundle\Exception\TokenInvalidException;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordConfirmEndpointType extends AbstractFormEndpointType
{
    use TemplateResolverTrait;

    public function __construct(
        private readonly ConfigurationProvider $provider,
        private readonly UserManager $userManager,
        private readonly UserRepository $userRepository,
    )
    {
    }

    protected function init($options, Request $request, Data $data, Context $context): void
    {
        $token = $request->get('token');
        $user = $this->userRepository->findByConfirmationToken($token);
        $context->set('targetUser', $user);
        $data->set('token', $token);

        if (null === $user) {
            throw new TokenInvalidException(sprintf('A user with confirmation token "%s" does not exist', $token));
        }
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
        if ($configuration->isAutoLogin()) {
            $this->userManager->login($user);
        }
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
