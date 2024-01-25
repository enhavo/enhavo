<?php

namespace Enhavo\Bundle\UserBundle\Endpoint\Type\ResetPassword;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResetPasswordCheckEndpointType extends AbstractEndpointType
{
    use TemplateResolverTrait;

    public function __construct(
        private readonly ConfigurationProvider $provider,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {

    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $configuration = $this->provider->getResetPasswordCheckConfiguration();

        $resolver->setDefaults([
            'template' => $this->resolveTemplate($configuration->getTemplate()),
        ]);
    }
}
