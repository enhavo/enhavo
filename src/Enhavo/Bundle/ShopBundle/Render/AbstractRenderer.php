<?php
/**
 * RendererInterface.php
 *
 * @since 27/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Render;


use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractRenderer implements RendererInterface
{
    use ContainerAwareTrait;

    /**
     * @param $id
     * @return object
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    public function renderTemplate($template, $parameters = [])
    {
        return $this->get('templating')->render($template, $parameters);
    }

    public function resolveOptions($defaultOptions, $options)
    {
        $optionResolver = new OptionsResolver();
        $optionResolver->setDefaults($defaultOptions);
        return $optionResolver->resolve($options);
    }
}