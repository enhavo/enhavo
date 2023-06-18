<?php

namespace Enhavo\Bundle\VueFormBundle\Form;

use Symfony\Component\Form\FormView;

class VueForm
{
    use VueDataHelperTrait;

    public function createData(FormView $formView)
    {
        $data = $this->getVueData($formView);

        $this->assembleRelations($data);

        $data['root'] = true;
        $data['method'] = !empty($formView->vars['method']) ? $formView->vars['method'] : null;
        $data['action'] = !empty($formView->vars['action']) ? $formView->vars['action'] : null;

        $this->callNormalizer($data);

        return $data->toArray();
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

    public function submit(array $data)
    {
        if ($data['compound']) {
            $returnData = [];
            foreach ($data['children'] as $child) {
                $returnData[$child['name']] = $this->submit($child);
            }
            return $returnData;
        }

        return $data['value'];
    }
}
