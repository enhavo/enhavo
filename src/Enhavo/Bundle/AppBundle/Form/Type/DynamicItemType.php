<?php
/**
 * ItemType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Enhavo\Bundle\GridBundle\Item\ItemTypeResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Enhavo\Bundle\GridBundle\Entity\Item;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DynamicItemType extends AbstractType
{
    /**
     * @var ItemTypeResolver
     */
    protected $resolver;

    /**
     * @var string
     */
    protected $class;

    public function __construct($class, ItemTypeResolver $itemTypeResolver)
    {
        $this->class = $class;
        $this->resolver = $itemTypeResolver;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('order', 'hidden', array(
            'data' => 0
        ));

        $builder->add('type', 'hidden');

        $resolver = $this->resolver;
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($resolver){
            $item = $event->getData();
            $form = $event->getForm();
            if(!empty($item) && isset($item['type'])) {
                $form->add('itemType', $resolver->getFormType($item['type']));
            }
        });

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($resolver){
            $item = $event->getData();
            $form = $event->getForm();
            if(!empty($item) && $item->getType()) {
                $form->add('itemType', $resolver->getFormType($item->getType()));
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $data = $form->getData();
        if($data instanceof Item) {
            $view->vars['label'] = $this->resolver->getLabel($data->getType());
        }

        return;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class
        ));
    }

    public function getName()
    {
        return 'enhavo_app_dynamic_item';
    }
} 