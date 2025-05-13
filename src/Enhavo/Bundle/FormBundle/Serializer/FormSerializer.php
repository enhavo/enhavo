<?php


namespace Enhavo\Bundle\FormBundle\Serializer;

use Enhavo\Bundle\FormBundle\Serializer\Encoder\Encoder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;

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
     * @param $formType
     * @return FormInterface
     */
    private function getForm($formType)
    {
        if(is_string($formType)) {
            return $this->formFactory->create($formType);
        }

        if($formType instanceof FormTypeInterface) {
            return $this->formFactory->create($formType);
        }

        if($formType instanceof FormBuilderInterface) {
            return $formType;
        }

        if($formType instanceof FormInterface) {
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
