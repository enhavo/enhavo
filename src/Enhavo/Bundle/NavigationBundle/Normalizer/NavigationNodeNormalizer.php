<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\Normalizer;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Normalizer\AbstractDataNormalizer;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Navigation\NavigationManager;
use Enhavo\Bundle\NavigationBundle\NavItem\NavItemManager;

class NavigationNodeNormalizer extends AbstractDataNormalizer
{
    use TemplateResolverTrait;

    public function __construct(
        private readonly NavigationManager $navigationManager,
        private readonly NavItemManager $itemManager,
    ) {
    }

    public function buildData(Data $data, $object, ?string $format = null, array $context = [])
    {
        if (!$this->hasSerializationGroup('endpoint.navigation', $context)) {
            return;
        }

        /* @var NodeInterface $object */
        $data->set('active', $this->navigationManager->isActive($object));

        $item = $this->itemManager->getNavItem($object->getName());
        $data->set('template', $this->resolveTemplate($item->getTemplate()));
        $data->set('component', $item->getComponent());
    }

    public static function getSupportedTypes(): array
    {
        return [NodeInterface::class];
    }
}
