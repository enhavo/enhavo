<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ColumnType extends AbstractType
{
    /** @var string */
    private $styleForm;

    /** @var string */
    private $widthForm;

    public function __construct($widthForm, $styleForm)
    {
        $this->widthForm = $widthForm;
        $this->styleForm = $styleForm;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('width', $this->widthForm);
        $builder->add('style', $this->styleForm);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_block_column';
    }
}
