<?php

namespace Enhavo\Bundle\VueFormBundle\Form;

use Symfony\Component\Form\FormView;

class VueForm
{
    public function createData(FormView $formView)
    {
        $data = $this->normalize($formView);
        $data['root'] = true;
        $data['rendered'] = true;
        return $data;
    }

    private function normalize(FormView $formView)
    {
        $array = $formView->vars['vue']->toArray();
        $array['children'] = [];
        $array['root'] = false;
        $array['rendered'] = false;

        foreach ($formView->children as $key => $child) {
            $array['children'][$key] = $this->normalize($child);
        }

        return $array;
    }

    public function submit(array $data)
    {
        if ($data['compound']) {
            $returnData = [];
            foreach ($data['children'] as $key => $child) {
                $returnData[$child['name']] = $this->submit($child);
            }
            return $returnData;
        }

        return $data['value'];
    }
}
