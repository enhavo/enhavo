<?php

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
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginEndpointType extends AbstractFormEndpointType
{
    use TemplateResolverTrait;
    use TargetPathTrait;

    public function __construct(
        private readonly ConfigurationProvider $provider,
        private readonly FirewallMap $firewallMap,
    )
    {
    }

    protected function init($options, Request $request, Data $data, Context $context): void
    {
        $configuration = $this->provider->getLoginConfiguration();

        $url = null;
        if ($this->isGranted('ROLE_USER')) {
            $url = $this->generateUrl($configuration->getRedirectRoute());
        }

        $context->set('redirect', $url);
        if ($request->get('_format') === 'html') {
            $context->setResponse(new RedirectResponse($url));
        }
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
        $firewallName = $this->firewallMap->getFirewallConfig($request)->getName();

        $targetPath = $request->get('_target_path') ?? $this->getTargetPath($request->getSession(), $firewallName);
        $this->removeTargetPath($request->getSession(), $firewallName);
        $request->getSession()->set('_security.credentials', null);

        return $targetPath;
    }

    protected function handleFailed($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {
        $failurePath = $request->get('_failure_path');

        if ($failurePath) {
            if ($request->get('_format') === 'html') {
                $context->setResponse(new RedirectResponse($failurePath));
            } else {
                $data->set('redirect', $failurePath);
            }
        }
    }

    protected function final($options, Request $request, Data $data, Context $context, FormInterface $form): void
    {
        if ($context->has('redirect')) {
            $data->set('redirect', $context->get('redirect'));
        } else if ($data->has('redirect')) {
            $data->set('redirect', null);
        }

        $data->set('viewId', $request->getSession()->get('enhavo.view_id'));
    }

    public static function getName(): ?string
    {
        return 'user_login';
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
        ]);
    }
}
