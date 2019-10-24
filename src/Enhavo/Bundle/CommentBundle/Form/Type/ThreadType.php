<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-09-16
 * Time: 15:18
 */

namespace Enhavo\Bundle\CommentBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\ListType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreadType extends AbstractType
{
    /**
     * @var string
     */
    private $dataClass;

    /**
     * ThreadType constructor.
     * @param $dataClass
     */
    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('comments', ListType::class, [
            'entry_type' => CommentType::class,
            'border' => true
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
        ]);
    }
}
