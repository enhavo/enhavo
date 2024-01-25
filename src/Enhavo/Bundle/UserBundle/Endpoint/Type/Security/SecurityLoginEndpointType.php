<?php

namespace Enhavo\Bundle\UserBundle\Endpoint\Type\Security;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Security\Authentication\AuthenticationError;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityLoginEndpointType extends AbstractEndpointType
{
    use TemplateResolverTrait;

    public function __construct(
        private readonly ConfigurationProvider $provider,
        private readonly CsrfTokenManagerInterface $tokenManager,
        private readonly AuthenticationUtils $authenticationUtils,
        private readonly AuthenticationError $authenticationError,
        private readonly VueForm $vueForm,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $configuration = $this->provider->getLoginConfiguration();

        $url = null;
        if ($this->isGranted('ROLE_USER')) {
            $url = $this->generateUrl($configuration->getRedirectRoute());
        }

        if ($request->get('_format') === 'html') {
            $context->setResponse(new RedirectResponse($url));
            return;
        }

        $error = $this->authenticationError->getError();
        $lastUsername = $this->authenticationUtils->getLastUsername(); // last username entered by the user
        $csrfToken = $this->tokenManager?->getToken('authenticate')->getValue();

        $form = $this->createForm($configuration->getFormClass(), null, $configuration->getFormOptions());

        $data->set('lastUsername', $lastUsername);
        $data->set('csrfToken', $csrfToken);
        $data->set('error', $error);
        $data->set('form', $this->vueForm->createData($form->createView()));
        $data->set('redirect', $url);
        $data->set('viewId', $request->getSession()->get('enhavo.view_id'));

        if ($error !== null) {
            $context->setStatusCode(400);
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
        ]);
    }
}
