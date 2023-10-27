<?php

namespace Enhavo\Bundle\NavigationBundle\Normalizer;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Normalizer\AbstractDataNormalizer;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverInterface;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\NavigationBundle\Entity\Content;
use Enhavo\Bundle\NavigationBundle\Entity\Link;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Navigation\NavigationManager;
use Enhavo\Bundle\NavigationBundle\NavItem\NavItemManager;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class NavigationNodeNormalizer extends AbstractDataNormalizer implements NormalizerAwareInterface
{
    use NormalizerAwareTrait;
    use TemplateResolverTrait;

    public function __construct(
        private NavigationManager $navigationManager,
        private NavItemManager $itemManager,
    ) {}

    public function buildData(Data $data, $object, string $format = null, array $context = [])
    {
        if (!$this->hasSerializationGroup('endpoint.navigation', $context)) {
            return;
        }

        /** @var NodeInterface $object */

        $data->set('active', $this->navigationManager->isActive($object));

        $item = $this->itemManager->getNavItem($object->getName());
        $data->set('template', $this->resolveTemplate($item->getTemplate()));
    }

    public static function getSupportedTypes(): array
    {
        return [NodeInterface::class];
    }
}
