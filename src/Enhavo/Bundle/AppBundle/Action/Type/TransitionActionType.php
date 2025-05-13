<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\ResourceBundle\Action\Type\SaveActionType;
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
            if (null !== $options['graph']) {
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
