<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\Normalizer;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Normalizer\AbstractDataNormalizer;
use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\RoutingBundle\Router\Router;

class ContentDataNormalizer extends AbstractDataNormalizer
{
    public function __construct(
        private Router $router,
    ) {
    }

    public function buildData(Data $data, $object, ?string $format = null, array $context = [])
    {
        /* @var $object Content */
        if (!$this->hasSerializationGroup(['endpoint', 'endpoint.navigation', 'endpoint.block'], $context)) {
            return;
        }

        $data->set('url', $this->router->generate($object));
    }

    public static function getSupportedTypes(): array
    {
        return [Content::class];
    }
}
