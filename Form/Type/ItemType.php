<?php
/**
 * ItemType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class ItemType extends AbstractType
{
    protected $typeService;

    public function __construct($typeService)
    {
        $this->typeService = $typeService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('order', 'hidden', array(
            'data' => 0
        ));
        $builder->add('configuration', new ConfigurationType($this->typeService));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'esperanto\ContentBundle\Entity\Item'
        ));
    }

    public function getName()
    {
        return 'esperanto_content_item';
    }
} 