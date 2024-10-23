<?php

namespace Enhavo\Bundle\AppBundle\Tests\Mock;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormFactoryMock implements FormFactoryInterface
{
    public function __construct(
        private readonly ?FormInterface $form = null,
        private readonly ?FormBuilderInterface $formBuilder = null,
    )
    {
    }

    public function create(string $type = FormType::class, mixed $data = null, array $options = []): FormInterface
    {
        return $this->form;
    }

    public function createNamed(string $name, string $type = FormType::class, mixed $data = null, array $options = []): FormInterface
    {
        return $this->form;
    }

    public function createForProperty(string $class, string $property, mixed $data = null, array $options = []): FormInterface
    {
        return $this->form;
    }

    public function createBuilder(string $type = FormType::class, mixed $data = null, array $options = []): FormBuilderInterface
    {
        return $this->formBuilder;
    }

    public function createNamedBuilder(string $name, string $type = FormType::class, mixed $data = null, array $options = []): FormBuilderInterface
    {
        return $this->formBuilder;
    }

    public function createBuilderForProperty(string $class, string $property, mixed $data = null, array $options = []): FormBuilderInterface
    {
        return $this->formBuilder;
    }
}
