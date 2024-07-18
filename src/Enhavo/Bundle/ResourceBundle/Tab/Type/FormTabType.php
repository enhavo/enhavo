<?php

namespace Enhavo\Bundle\ResourceBundle\Tab\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Input\InputInterface;
use Enhavo\Bundle\ResourceBundle\Tab\AbstractTabType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormTabType extends AbstractTabType
{
    public function createViewData(array $options, Data $data, InputInterface $input = null): void
    {
        $data->set('arrangement', $options['arrangement']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'tab-form',
            'arrangement' => null,
        ]);
    }

    public static function getName(): ?string
    {
        return 'form';
    }
}
