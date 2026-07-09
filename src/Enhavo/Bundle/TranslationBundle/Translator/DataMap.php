<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Translator;

class DataMap
{
    private $map = [];

    public function exists($entity)
    {
        $oid = spl_object_hash($entity);
        return array_key_exists($oid, $this->map);
    }

    public function store($entity, $property, $locale, $data)
    {
        $oid = spl_object_hash($entity);
        if (!isset($this->map[$oid])) {
            $this->map[$oid] = [];
        } else {
            $entry = $this->find($this->map[$oid], $property, $locale);
            if (null !== $entry) {
                $entry->setData($data);

                return;
            }
        }

        $this->map[$oid][] = new DataMapEntry($property, $locale, $data);
    }

    public function load($entity, $property, $locale)
    {
        $oid = spl_object_hash($entity);
        if (!isset($this->map[$oid])) {
            return null;
        }

        $entry = $this->find($this->map[$oid], $property, $locale);
        if (null !== $entry) {
            return $entry->getData();
        }

        return null;
    }

    public function delete($entity, $property, $locale)
    {
        $oid = spl_object_hash($entity);
        if (!isset($this->map[$oid])) {
            return null;
        }

        $deleteKey = null;
        foreach ($this->map[$oid] as $key => $entry) {
            if ($entry->getProperty() === $property && $entry->getLocale() === $locale) {
                $deleteKey = $key;
                break;
            }
        }

        if ($deleteKey !== null) {
            unset($this->map[$oid][$deleteKey]);
        }

        if (count($this->map[$oid]) === 0) {
            unset($this->map[$oid]);
        }
    }

    /**
     * @param DataMapEntry[] $entries
     * @param string|null    $property
     * @param string|null    $locale
     */
    private function find(array $entries, $property, $locale): ?DataMapEntry
    {
        foreach ($entries as $entry) {
            if ($entry->getProperty() === $property && $entry->getLocale() === $locale) {
                return $entry;
            }
        }

        return null;
    }
}
