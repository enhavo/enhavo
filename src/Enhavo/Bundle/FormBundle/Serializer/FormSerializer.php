<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Serializer;

use Enhavo\Bundle\FormBundle\Serializer\Encoder\Encoder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @author gseidel
 */
class FormSerializer
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var Encoder
     */
    protected $encoder;

    public function __construct(FormFactoryInterface $formFactory, Encoder $encoder)
    {
        $this->formFactory = $formFactory;
        $this->encoder = $encoder;
    }

    /**
     * @return FormInterface
     */
    private function getForm($formType)
    {
        if (is_string($formType)) {
            return $this->formFactory->create($formType);
        }

        if ($formType instanceof FormTypeInterface) {
            return $this->formFactory->create($formType);
        }

        if ($formType instanceof FormBuilderInterface) {
            return $formType;
        }

        if ($formType instanceof FormInterface) {
            return $formType;
        }

        throw new \InvalidArgumentException('form type not supported');
    }

    public function serialize($data, $formType, $format)
    {
        $form = $this->getForm($formType);
        $form->setData($data);
        $data = $this->serializeForm($form);
        $encoder = $this->encoder->getEncoder($format);

        return $encoder->encode($data);
    }

    private function serializeForm(FormInterface $form)
    {
        if (!$form->all()) {
            return $form->getViewData();
        }
        $data = null;
        foreach ($form->all() as $child) {
            $name = $child->getConfig()->getName();
            $data[$name] = $this->serializeForm($child);
        }

        return $data;
    }
}
