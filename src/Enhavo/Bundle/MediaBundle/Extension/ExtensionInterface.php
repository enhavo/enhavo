<?php

namespace Enhavo\Bundle\MediaBundle\Extension;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Symfony\Component\Form\FormBuilderInterface;

interface ExtensionInterface extends TypeInterface
{
    public function renderExtension($options);

    public function renderButton($options);

    public function buildForm(FormBuilderInterface $builder, $options);
}