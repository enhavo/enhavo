<?php

namespace Enhavo\Bundle\ResourceBundle\Form;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Schema;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\Form\FormInterface;

class FormDescriber implements FormDescriberInterface
{
    private ?ServiceLocator $container = null;

    public function setContainer(ServiceLocator $container)
    {
        $this->container = $container;
    }

    public function describe(FormInterface $form, Schema $schema): void
    {
        $type = $form->getConfig()->getType()->getInnerType();
        if (is_a($type, FormTypeDescribeAwareInterface::class)) {
            $type->describe($form->getConfig()->getOptions(), $type, $schema);
            return;
        }

        $parentType = $form->getConfig()->getType()->getParent();
        while ($parentType != null) {
            $parentInnerType = $parentType->getInnerType();
            if (is_a($parentInnerType, FormTypeDescribeAwareInterface::class)) {
                $parentInnerType->describe($form->getConfig()->getOptions(), $type, $schema);
                return;
            }
            $parentType = $parentType->getParent();
        }

        foreach (array_keys($this->container->getProvidedServices()) as $serviceName) {
            /** @var FormTypeDescriberInterface $serviceName */
            $types = $serviceName::getFormTypes();
            foreach ($types as $findType) {
                if (is_a($type, $findType)) {
                    $describeType = $this->container->get($serviceName);
                    $describeType->describe($form->getConfig()->getOptions(), $type, $schema);
                    return;
                }

                $parentType = $form->getConfig()->getType()->getParent();
                while ($parentType != null) {
                    if (is_a($parentType->getInnerType(), $findType)) {
                        $describeType = $this->container->get($serviceName);
                        $describeType->describe($form->getConfig()->getOptions(), $type, $schema);
                        return;
                    }
                    $parentType = $parentType->getParent();
                }
            }
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
}
