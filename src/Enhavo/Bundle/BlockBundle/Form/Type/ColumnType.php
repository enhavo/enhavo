<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 06.08.18
 * Time: 11:09
 */

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ColumnType extends AbstractType
{
    /**
     * @var string
     */
    private $styleForm;

    /**
     * @var string
     */
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
        return 'enhavo_grid_column';
    }
}