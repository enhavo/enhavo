<?php
/**
 * ContentType.php
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

class ContentType extends AbstractType
{
    protected $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('items', 'collection', array(
            'type' => 'esperanto_content_item',
            'allow_delete' => true,
            'allow_add'    => true,
            'by_reference' => false
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['item_types'] = $options['item_types'];
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'esperanto\ContentBundle\Entity\Content',
            'item_types' => array(array('type' => 'text','label' => 'Text'),array('type' => 'picture','label' => 'Bild'),array('type' => 'video','label' => 'Video'))
        ));
    }

    public function getName()
    {
        return 'esperanto_content';
    }
}
