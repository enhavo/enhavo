<?php

namespace Enhavo\Bundle\AppBundle\Batch;

use Enhavo\Bundle\AppBundle\Type\AbstractType;

abstract class AbstractBatch extends AbstractType implements BatchInterface
{
    protected $options;

    public function setOptions($parameters)
    {
        $this->options = [
            'permission' => isset($parameters['permission']) ? $parameters['permission'] : null,
            'position' => isset($parameters['position']) ? $parameters['position'] : 0,
            'label' => isset($parameters['label']) ? $parameters['label'] : null,
            'confirmMessage' => isset($parameters['confirmMessage']) ? $parameters['confirmMessage'] : null,
            'translationDomain' => isset($parameters['translationDomain']) ? $parameters['translationDomain'] : null,
            'display' => isset($parameters['display']) ? $parameters['display'] : true
        ];
    }

    public function getConfirmMessage()
    {
        if($this->options['confirmMessage'] === null) {
            return null;
        }

        return $this->container->get('translator')->trans(
            $this->options['confirmMessage'],
            [],
            $this->options['translationDomain']
        );
    }

    public function getLabel()
    {
        if($this->options['label'] === null) {
            return null;
        }

        return $this->container->get('translator')->trans(
            $this->options['label'],
            [],
            $this->options['translationDomain']
        );
    }

    public function isGranted()
    {
        $permission = $this->options['permission'];
        if($permission === null) {
            return true;
        }
        return $this->container->get('security.authorization_checker')->isGranted($permission);
    }

    public function getDisplay()
    {
        return $this->options['display'];
    }
}
