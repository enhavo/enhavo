<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-29
 * Time: 00:23
 */

namespace Enhavo\Bundle\TranslationBundle\Translator;


use Enhavo\Bundle\TranslationBundle\Entity\Translation;

class DataMap
{
    private $map = [];

    public function store($entity, $property, $locale, Translation $data)
    {
        $oid = spl_object_hash($entity);
        if(!isset($this->map[$oid])) {
            $this->map[$oid] = [];
        }
        $this->map[$oid][$property] = $data;
    }

    public function load($entity, $property, $locale): ?Translation
    {
        $oid = spl_object_hash($entity);
        if(!isset($this->map[$oid])) {
            return null;
        }

        if(!isset($this->map[$oid][$property])) {
            return null;
        }

        return $this->map[$oid][$property];
    }
}
