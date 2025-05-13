<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Endpoint\Type\ChangeEmail;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AbstractFormEndpointType;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Form\Data\ChangeEmail;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangeEmailConfirmEndpointType extends AbstractFormEndpointType
{
    use TemplateResolverTrait;

    public function __construct(
        private readonly ConfigurationProvider $provider,
        private readonly UserManager $userManager,
        private readonly TranslatorInterface $translator,
        private readonly UserRepository $userRepository,
    ) {
    }

    protected function init($options, Request $request, Data $data, Context $context): void
    {
        $token = $request->get('token');
        $user = $this->userRepository->findByConfirmationToken($request->get('token'));

        $context->set('targetUser', $user);
        $data->set('token', $token);

        if (null === $user) {
            throw $this->createNotFoundException(sprintf('A user with confirmation token "%s" does not exist', $token));
        }
    }

    protected function getForm($options, Request $request, Data $data, Context $context): FormInterface
    {
        $configuration = $this->provider->getChangeEmailConfirmConfiguration();
        $changeEmail = new ChangeEmail();

        return $this->createForm($configuration->getFormClass(), $changeEmail, $configuration->getFormOptions());
    }

    protected function handleSuccess($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {
        $configuration = $this->provider->getChangeEmailConfirmConfiguration();
        $changeEmail = $form->getData();
        $user = $context->get('targetUser');

        $this->userManager->changeEmail($user, $changeEmail->getEmail(), $configuration);
        if ($configuration->isAutoLogin()) {
            $this->userManager->login($user);
        }

        $data->set('message', $this->translator->trans('change_email.flash.message.success', [], 'EnhavoUserBundle'));
    }

    protected function handleFailed($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {
        $data->set('message', $this->translator->trans('change_email.flash.message.error', [], 'EnhavoUserBundle'));
    }

    protected function getRedirectUrl($options, Request $request, Data $data, Context $context, FormInterface $form): ?string
    {
        $configuration = $this->provider->getChangeEmailConfirmConfiguration();
        $route = $configuration->getRedirectRoute();

        return $route ?? $this->generateUrl($route);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $configuration = $this->provider->getChangeEmailConfirmConfiguration();

        $resolver->setDefaults([
            'template' => $this->resolveTemplate($configuration->getTemplate()),
        ]);
    }
}
