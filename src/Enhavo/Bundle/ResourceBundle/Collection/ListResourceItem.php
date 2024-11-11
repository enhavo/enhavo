<?php

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
