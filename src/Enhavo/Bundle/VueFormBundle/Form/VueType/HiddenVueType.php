<?php

namespace Enhavo\Bundle\VueFormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueTypeInterface;
use Symfony\Component\Form\FormView;

class HiddenVueType implements VueTypeInterface
{
    public function getComponent(): ?string
    {
        return 'form-hidden';
    }

    public static function getBlocks(): array
    {
        return ['hidden' => 1];
    }

    public function buildView(FormView $view, VueData $data)
    {
        $data['rowComponent'] = 'form-hidden-row';
    }
}
