<?php

namespace Enhavo\Bundle\VueFormBundle\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

interface VueTypeInterface
{
    public function getComponent($options): string;

    public static function getFormTypes(): array;

    public function buildView(FormView $view, FormInterface $form, array $options, VueData $data);

    public function finishView(FormView $view, FormInterface $form, array $options, VueData $data);
}
