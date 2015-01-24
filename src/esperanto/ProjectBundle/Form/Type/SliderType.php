<?php
/**
 * SliderType.php
 *
 * @since 31/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ProjectBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use esperanto\SliderBundle\Form\Type\SliderType as BaseSliderType;

class SliderType extends BaseSliderType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }
}