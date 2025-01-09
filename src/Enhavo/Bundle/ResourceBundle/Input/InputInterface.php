<?php

namespace Enhavo\Bundle\ResourceBundle\Input;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface InputInterface
{
    public function setOptions($options): void;

    public function configureOptions(OptionsResolver $resolver): void;

    public function createForm(mixed $data = null, array $context = []): ?FormInterface;

    public function getResource(array $context = []): ?object;

    public function setResource(?object $resource): void;

    public function getViewData(object $resource, array $context = []): array;
}
