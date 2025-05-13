<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Collection;

class ListResourceItem extends ResourceItem
{
    public function normalize(): array
    {
        $normalizedData = parent::normalize();

        if (isset($normalizedData['children']) && is_array($normalizedData['children'])) {
            foreach ($normalizedData['children'] as $key => $child) {
                $normalizedData['children'][$key] = $child->normalize();
            }
        }

        return $normalizedData;
    }
}
