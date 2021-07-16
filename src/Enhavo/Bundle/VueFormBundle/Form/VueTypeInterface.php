<?php

namespace Enhavo\Bundle\VueFormBundle\Form;

use Symfony\Component\Form\FormView;

interface VueTypeInterface
{
    public function getComponent(): ?string;

    public static function getBlocks(): array;

    public function buildView(FormView $view, VueData $data);
}
