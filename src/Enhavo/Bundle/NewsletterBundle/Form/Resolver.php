<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 09.12.16
 * Time: 14:40
 */

namespace Enhavo\Bundle\NewsletterBundle\Form;


class Resolver
{
    private $formConfig;

    private $defaultGroups;

    public function __construct($formConfig, $defaultGroups)
    {
        $this->formConfig = $formConfig;
        $this->defaultGroups = $defaultGroups;
    }

    public function resolveType($formType)
    {
        if (key_exists($formType, $this->formConfig)) {
            return $this->formConfig[$formType]['type'];
        }
        return null;
    }

    public function resolveTemplate($formType)
    {
        if (key_exists($formType, $this->formConfig)) {
            return $this->formConfig[$formType]['template'];
        }
        return null;
    }

    public function resolveGroups($formType)
    {
        if (isset($this->formConfig[$formType]['storage']['options']['groups'])) {
            $formGroups = $this->formConfig[$formType]['storage']['options']['groups'];
        } else {
            $formGroups = [];
        }
        return $this->mergeArrays($this->defaultGroups, $formGroups);
    }

    protected function mergeArrays($array, $arrayToAdd) {
        foreach ($arrayToAdd as $toAdd) {
            if (!in_array($toAdd, $array)) {
                $array[] = $toAdd;
            }
        }
        return $array;
    }
}