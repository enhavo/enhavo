<?php
/**
 * StrategyResolver.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator;


use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class StrategyResolver
{
    use ContainerAwareTrait;

    /**
     * @param string $strategy
     * @return TranslationStrategyInterface
     */
    public function getStrategy($strategy)
    {
        if($strategy == 'route_translation') {
            return $this->container->get('enhavo_translation.strategy.route_translation');
        }

        if($strategy == 'slug_translation') {
            return $this->container->get('enhavo_translation.strategy.slug_translation');
        }

        return $this->container->get('enhavo_translation.strategy.translation_table');
    }

    /**
     * @return TranslationStrategyInterface[]
     */
    public function getStrategies()
    {
        return [
            $this->container->get('enhavo_translation.strategy.translation_table'),
            $this->container->get('enhavo_translation.strategy.route_translation'),
            $this->container->get('enhavo_translation.strategy.slug_translation')
        ];
    }
}