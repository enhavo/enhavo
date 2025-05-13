<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Template;

use Psr\Container\ContainerInterface;

/**
 * Trait TemplateControllerTrait
 *
 * @property ContainerInterface $container
 */
trait TemplateResolverTrait
{
    protected ?TemplateResolverInterface $templateResolver = null;

    public function setTemplateResolver(TemplateResolverInterface $templateResolver): void
    {
        $this->templateResolver = $templateResolver;
    }

    public function resolveTemplate(string $name): string
    {
        return $this->templateResolver->resolve($name);
    }
}
