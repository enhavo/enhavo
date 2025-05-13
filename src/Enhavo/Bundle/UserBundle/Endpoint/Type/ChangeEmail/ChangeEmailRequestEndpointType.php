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
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangeEmailRequestEndpointType extends AbstractFormEndpointType
{
    use TemplateResolverTrait;

    public function __construct(
        private readonly ConfigurationProvider $provider,
        private readonly UserManager $userManager,
        private readonly TranslatorInterface $translator,
    ) {
    }

    protected function getForm($options, Request $request, Data $data, Context $context): FormInterface
    {
        $configuration = $this->provider->getChangeEmailRequestConfiguration();
        $user = $this->getUser();

        return $this->createForm($configuration->getFormClass(), $user, $configuration->getFormOptions());
    }

    protected function handleSuccess($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {
        $configuration = $this->provider->getChangeEmailRequestConfiguration();
        $this->userManager->requestChangeEmail($form->getData(), $configuration);
        $data->set('message', $this->translator->trans('change_email.flash.message.success', [], 'EnhavoUserBundle'));
    }

    protected function handleFailed($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {
        $data->set('message', $this->translator->trans('change_email.flash.message.error', [], 'EnhavoUserBundle'));
    }

    protected function getRedirectUrl($options, Request $request, Data $data, Context $context, FormInterface $form): ?string
    {
        $configuration = $this->provider->getChangeEmailRequestConfiguration();
        $route = $configuration->getRedirectRoute();

        return $route ?? $this->generateUrl($route);
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $configuration = $this->provider->getChangeEmailRequestConfiguration();

        $resolver->setDefaults([
            'template' => $this->resolveTemplate($configuration->getTemplate()),
        ]);
    }
}
