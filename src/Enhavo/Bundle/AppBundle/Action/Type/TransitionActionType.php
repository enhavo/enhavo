<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class TransitionActionType extends SaveActionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'graph' => null,
        ]);

        $resolver->setNormalizer('route_parameters', function ($options, $value) {
            $value['transition'] = $options['transition'];
            if ($options['graph'] !== null) {
                $value['graph'] = $options['graph'];
            }
            return $value;
        });

        $resolver->setRequired(['transition']);
    }

    public function getType()
    {
        return 'transition';
    }
}
