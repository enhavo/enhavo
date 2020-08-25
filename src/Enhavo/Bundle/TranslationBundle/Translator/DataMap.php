<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-29
 * Time: 00:23
 */

namespace Enhavo\Bundle\TranslationBundle\Translator;

class DataMap
{
    private $map = [];

    public function store($entity, $property, $locale, $data)
    {
        $oid = spl_object_hash($entity);
        if (!isset($this->map[$oid])) {
            $this->map[$oid] = [];
        } else {
            $entry = $this->find($this->map[$oid], $property, $locale);
            if ($entry !== null) {
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
        if ($entry !== null) {
            return $entry->getData();
        }

        return null;
    }

    public function delete($entity)
    {
        $oid = spl_object_hash($entity);
        if (isset($this->map[$oid])) {
            unset($this->map[$oid]);
        }
    }

    /**
     * @param DataMapEntry[] $entries
     * @param string|null $property
     * @param string|null $locale
     * @return DataMapEntry|null
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
