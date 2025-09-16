<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\StructuredData;

use Enhavo\Bundle\ApiBundle\Data\Data;

class StructuredData extends Data
{
    private ?string $type;
    private bool $root = true;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function isRoot(): bool
    {
        return $this->root;
    }

    public function setRoot(bool $root): void
    {
        $this->root = $root;
    }

    public function normalize(): array
    {
        $data = [];
        if ($this->root) {
            $data['@context'] = 'http://schema.org';
        }

        if ($this->type) {
            $data['@type'] = $this->type;
        }

        return array_merge($data, $this->normalizeData($this->data));
    }

    private function normalizeData(iterable $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if ($value instanceof StructuredData) {
                if ($value->count() > 0) {
                    $result[$key] = $value->normalize();
                }
            } elseif (is_iterable($value)) {
                $result[$key] = $this->normalizeData($value);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
