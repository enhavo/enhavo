<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Schema;
use Enhavo\Bundle\ResourceBundle\Form\FormTypeDescribeAwareInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */
class WysiwygType extends AbstractType implements FormTypeDescribeAwareInterface
{
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['configName'] = $options['config'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'form-wysiwyg',
            'component_model' => 'WysiwygForm',
            'config' => 'default',
        ]);
    }

    public function getParent()
    {
        return TextareaType::class;
    }

    public function describe($options, FormTypeInterface $form, Schema $schema)
    {
        $schema->string()
            ->format('html');
    }
}
