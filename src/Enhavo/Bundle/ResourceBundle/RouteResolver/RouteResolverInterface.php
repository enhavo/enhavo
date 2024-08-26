<?php

namespace Enhavo\Bundle\ResourceBundle\RouteResolver;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface RouteResolverInterface
{
    public function getRoute(string $name, array $context = []): ?string;
}
