<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractUrlActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutputStreamActionType extends AbstractUrlActionType implements ActionTypeInterface
{
    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);

        $data = array_merge($data, [
            'modal' => [
                'component' => 'output-stream',
                'url' => $data['url'],
                'closeLabel' => $this->translator->trans('label.close', [], 'EnhavoAppBundle')
            ]
        ]);

        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'modal-action',
        ]);

        $resolver->setRequired(['route']);
    }

    public function getType()
    {
        return 'output_stream';
    }
}
