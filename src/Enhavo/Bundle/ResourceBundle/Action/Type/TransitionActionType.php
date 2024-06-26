<?php

namespace Enhavo\Bundle\ResourceBundle\Action\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class TransitionActionType extends SaveActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
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

    public static function getParentType(): ?string
    {
        return SaveActionType::class;
    }

    public static function getName(): ?string
    {
        return 'transition';
    }
}
