<?php

namespace Enhavo\Bundle\ResourceBundle\Form;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Schema;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

class FormDescriber implements FormDescriberInterface
{
    private ?ServiceLocator $container = null;

    public function setContainer(ServiceLocator $container)
    {
        $this->container = $container;
    }

    public function describe(FormInterface $form, Schema $schema): void
    {
        $innerType = $form->getConfig()->getType()->getInnerType();
        $options = $form->getConfig()->getOptions();

        if ($this->describeByFormType($options, $innerType, $schema)) {
            return;
        }

        $parentType = $form->getConfig()->getType()->getParent();
        while ($parentType != null) {
            $innerType = $parentType->getInnerType();

            if ($this->describeByFormType($options, $innerType, $schema)) {
                return;
            }

            $parentType = $parentType->getParent();
        }

        if ($form->getConfig()->getCompound()) {
            $object = $schema->object();
            foreach ($form as $property => $child) {
                $this->describe($child, $object->property($property, 'schema'));
            }
        } else {
            $schema->string();
        }
    }

    private function describeByFormType(array $options, FormTypeInterface $type, Schema $schema): bool
    {
        foreach (array_keys($this->container->getProvidedServices()) as $serviceName) {
            /** @var FormTypeDescriberInterface $serviceName */
            $types = $serviceName::getFormTypes();
            foreach ($types as $findType) {
                if (is_a($type, $findType)) {
                    $describeType = $this->container->get($serviceName);
                    $describeType->describe($options, $type, $schema);
                    return true;
                }
            }
        }

        if (is_a($type, FormTypeDescribeAwareInterface::class)) {
            $type->describe($options, $type, $schema);
            return true;
        }

        return false;
    }
}
