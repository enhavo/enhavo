<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\VueFormBundle\Form;

use Symfony\Component\Form\FormView;

class VueForm
{
    use VueDataHelperTrait;

    public function createData(FormView $formView, ?array $fields = null)
    {
        $data = $this->getVueData($formView);

        $this->assembleRelations($data);

        $data['root'] = true;
        $data['method'] = !empty($formView->vars['method']) ? $formView->vars['method'] : null;
        $data['action'] = !empty($formView->vars['action']) ? $formView->vars['action'] : null;

        $this->callNormalizer($data);

        $returnData = $data->toArray();

        if (is_array($fields)) {
            $returnData = $this->filterFields($returnData, $fields);
        }

        return $returnData;
    }

    private function assembleRelations(VueData $data)
    {
        $data['root'] = false;

        foreach ($data->getFormView()->children as $key => $child) {
            $data->addChild($key, $this->getVueData($child));
        }

        foreach ($data->getChildren() as $child) {
            $this->assembleRelations($child);
        }
    }

    private function callNormalizer(VueData $data)
    {
        foreach ($data->getNormalizer() as $normalizer) {
            $normalizer($data->getFormView(), $data);
        }

        foreach ($data->getChildren() as $child) {
            $this->callNormalizer($child);
        }
    }

    private function filterFields(array $returnData, array $fields): array
    {
        $data = [];
        foreach ($returnData as $key => $value) {
            if (in_array($key, $fields) || 'name' == $key) {
                $data[$key] = $value;
            }
        }

        $data['children'] = [];
        foreach ($returnData['children'] as $child) {
            $data['children'][] = $this->filterFields($child, $fields);
        }

        return $data;
    }
}
