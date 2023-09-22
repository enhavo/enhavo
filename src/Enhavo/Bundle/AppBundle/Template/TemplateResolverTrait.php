<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-07-08
 * Time: 16:00
 */

namespace Enhavo\Bundle\AppBundle\Template;

use Psr\Container\ContainerInterface;

/**
 * Trait TemplateControllerTrait
 * @package Enhavo\Bundle\AppBundle\Controller
 * @property ContainerInterface $container
 */
trait TemplateResolverTrait
{
    protected ?TemplateResolver $templateResolver = null;

    public function setTemplateResolver(TemplateResolver $templateResolver): void
    {
        $this->templateResolver = $templateResolver;
    }

    public function resolveTemplate(string $name): string
    {
        return $this->templateResolver->resolve($name);
    }
}
