<?php

namespace Enhavo\Bundle\UserBundle\Endpoint\Type\ChangeEmail;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ChangeEmailFinishEndpointType extends AbstractEndpointType
{
    use TemplateResolverTrait;

    public function __construct(
        private readonly ConfigurationProvider $provider,
    )
    {
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $configuration = $this->provider->getChangeEmailFinishConfiguration();

        $resolver->setDefaults([
            'template' => $this->resolveTemplate($configuration->getTemplate()),
        ]);
    }
}
