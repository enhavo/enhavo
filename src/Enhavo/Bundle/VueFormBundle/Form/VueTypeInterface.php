<?php

namespace Enhavo\Bundle\VueFormBundle\Form;

use Symfony\Component\Form\FormView;

interface VueTypeInterface
{
    public static function supports(FormView $formView): bool;

    public function buildView(FormView $view, VueData $data);
}
