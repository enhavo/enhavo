<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tab\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Input\InputInterface;
use Enhavo\Bundle\ResourceBundle\Tab\AbstractTabType;
use Enhavo\Bundle\ResourceBundle\Tab\ArrangementParser;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormTabType extends AbstractTabType
{
    public function __construct(
        private readonly ArrangementParser $arrangementParser,
    ) {
    }

    public function createViewData(array $options, Data $data, ?InputInterface $input = null): void
    {
        $data->set('arrangement', $this->arrangementParser->parse($options['arrangement']));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'tab-form',
            'arrangement' => null,
            'model' => 'FormTab',
        ]);
    }

    public static function getName(): ?string
    {
        return 'form';
    }
}
