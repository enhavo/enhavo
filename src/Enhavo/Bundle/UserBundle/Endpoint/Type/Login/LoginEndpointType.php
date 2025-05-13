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
use Enhavo\Bundle\AppBundle\Endpoint\Type\AbstractFormEndpointType;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginEndpointType extends AbstractFormEndpointType
{
    use TemplateResolverTrait;
    use TargetPathTrait;

    public function __construct(
        private readonly ConfigurationProvider $provider,
        private readonly FirewallMap $firewallMap,
        private readonly TokenStorageInterface $tokenStorage,
    ) {
    }

    protected function init($options, Request $request, Data $data, Context $context): void
    {
        if ($this->tokenStorage->getToken()) {
            $redirect = $this->getSuccessRedirect($request);

            if ('html' === $request->get('_format')) {
                $context->setResponse(new RedirectResponse($redirect));
            } else {
                $data->set('redirect', $redirect);
            }
        }

        $data->set('component', $options['component']);
        $data->set('props', $options['props']);
    }

    protected function getForm($options, Request $request, Data $data, Context $context): FormInterface
    {
        $configuration = $this->provider->getLoginConfiguration();

        return $this->createForm($configuration->getFormClass(), null, $configuration->getFormOptions());
    }

    protected function handleSuccess($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {
        // handled already by authenticator
    }

    protected function getRedirectUrl($options, Request $request, Data $data, Context $context, FormInterface $form): ?string
    {
        return $this->getSuccessRedirect($request);
    }

    private function getSuccessRedirect(Request $request)
    {
        $firewallName = $this->firewallMap->getFirewallConfig($request)->getName();

        $targetPath = $request->get('redirect') ?? $this->getTargetPath($request->getSession(), $firewallName);
        $this->removeTargetPath($request->getSession(), $firewallName);
        $request->getSession()->set('_security.credentials', null);

        if (null === $targetPath) {
            $configuration = $this->provider->getLoginConfiguration();
            $targetPath = $this->generateUrl($configuration->getRedirectRoute());
        }

        return $targetPath;
    }

    protected function handleFailed($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {
        $failurePath = $request->get('failureRedirect');

        if ($failurePath) {
            if ('html' === $request->get('_format')) {
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
