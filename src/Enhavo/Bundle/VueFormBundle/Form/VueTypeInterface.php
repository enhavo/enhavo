<?php

namespace Enhavo\Bundle\VueFormBundle\Form;

use Symfony\Component\Form\FormView;

interface VueTypeInterface
{
    //public function configureForm();

    public static function supports(FormView $formView): bool;

    public function buildView(FormView $view, VueData $data);

    public function finishView(FormView $view, VueData $data);
}
