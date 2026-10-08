<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Endpoint\Type\Login;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\FrameworkBundle\Endpoint\Type\AbstractFormEndpointType;
use Enhavo\Bundle\FrameworkBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\FrameworkBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Security\Authentication\AuthenticationError;
use Enhavo\Bundle\UserBundle\User\TargetPathResolver;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LoginEndpointType extends AbstractFormEndpointType
{
    use TemplateResolverTrait;

    public function __construct(
        private readonly ConfigurationProvider $provider,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly AuthenticationError $authenticationError,
        private readonly TargetPathResolver $targetPathResolver,
    ) {
    }

    protected function init($options, Request $request, Data $data, Context $context): void
    {
        if ($this->tokenStorage->getToken()) {
            $redirect = $this->targetPathResolver->resolveTargetPath($request);

            if ('html' === $request->attributes->get('_format')) {
                $context->setResponse(new RedirectResponse($redirect));

            } else {
                $data->set('redirect', $redirect);
            }
        }

        $data->set('component', $options['component']);
        $data->set('props', $options['props']);

        $error = $this->authenticationError->getError();
        $data->set('error', $error);
        $context->set('error', $error);
    }

    protected function getForm($options, Request $request, Data $data, Context $context): FormInterface
    {
        $configuration = $this->provider->getLoginConfiguration();

        return $this->createForm($configuration->getFormClass(), null, $configuration->getFormOptions());
    }

    protected function handleSuccess($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {
        // if success, then handled already by authenticator

        if ($context->get('error')) {
            $data->set('success', false);
            $context->setStatusCode(400);
        }
    }

    protected function getRedirectUrl($options, Request $request, Data $data, Context $context, FormInterface $form): ?string
    {
        if ($context->get('error')) {
            return null;
        }

        return $this->targetPathResolver->resolveTargetPath($request);
    }

    protected function handleFailed($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {
        $failurePath = $request->query->get('failureRedirect');

        if ($failurePath) {
            if ('html' === $request->attributes->get('_format')) {
                $context->setResponse(new RedirectResponse($failurePath));
            } else {
                $data->set('redirect', $failurePath);
            }
        }
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $configuration = $this->provider->getLoginConfiguration();

        $resolver->setDefaults([
            'template' => $this->resolveTemplate($configuration->getTemplate()),
            'component' => null,
            'props' => [],
        ]);
    }

    public static function getName(): ?string
    {
        return 'user_login';
    }
}
