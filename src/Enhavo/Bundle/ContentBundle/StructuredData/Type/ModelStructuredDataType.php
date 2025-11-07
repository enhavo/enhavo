<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\StructuredData\Type;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\ContentBundle\StructuredData\AbstractStructuredDataType;
use Enhavo\Bundle\ContentBundle\StructuredData\Context;
use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataBag;
use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataManager;

class ModelStructuredDataType extends AbstractStructuredDataType
{
    public function __construct(
        private StructuredDataManager $structuredDataManager,
    ) {
    }

    public function buildData(array $options, object $model, StructuredDataBag $bag, Context $context): void
    {
        if ($context->isPropertyAttribute()) {
            $value = $context->getPropertyValue();
            if ($value instanceof Collection) {
                foreach ($value as $item) {
                    $this->structuredDataManager->buildData($item, $bag, $options['groups']);
                }
            } else if ($value !== null) {
                $this->structuredDataManager->buildData($value, $bag, $options['groups']);
            }
        }
    }

    public function getTypeName(array $options): ?string
    {
        return null;
    }

    public static function getName(): ?string
    {
        return 'model';
    }
}
