<?php

namespace Enhavo\Bundle\ArticleBundle\Vue;

use Enhavo\Bundle\AppBundle\Vue\RouteProvider\AbstractVueRouteProviderType;
use Enhavo\Bundle\RoutingBundle\Vue\RoutingVueRouteProviderType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleVueRouteProviderType extends AbstractVueRouteProviderType
{
    public static function getName(): ?string
    {
        return 'article';
    }

    public static function getParentType(): ?string
    {
        return RoutingVueRouteProviderType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'resource_key' => 'enhavo_article.article',
            'meta_name' => 'article',
        ]);
    }
}
