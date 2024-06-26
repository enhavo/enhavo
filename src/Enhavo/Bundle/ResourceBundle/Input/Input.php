<?php

namespace Enhavo\Bundle\ResourceBundle\Input;

use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Input extends AbstractInput
{
    public function getActionViewData(ResourceInterface $resource = null, array $configuration = []): array
    {
        $actions = $this->getActions($this->mergeArray($this->options['actions'], $configuration));
        $data = [];
        foreach($actions as $action) {
            $data[] = $action->createViewData($resource);
        }
        return $data;
    }

    public function getActionSecondaryViewData(ResourceInterface $resource = null, array $configuration = []): array
    {
        $actions = $this->getActions($this->options['actions_secondary']);
        $data = [];
        foreach($actions as $action) {
            $data[] = $action->createViewData($resource);
        }
        return $data;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'actions' => [],
            'actions_secondary' => [],
        ]);

        $resolver->setRequired('resource');
    }

    private function mergeArray($array1, $array2): array
    {
        return [];
    }
}
