<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
