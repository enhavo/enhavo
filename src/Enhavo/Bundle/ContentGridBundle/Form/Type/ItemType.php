<?php
/**
 * ItemType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace enhavo\ContentBundle\Form\Type;

use enhavo\ContentBundle\Item\ItemTypeResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use enhavo\ContentBundle\Entity\Item;

class ItemType extends AbstractType
{
    /**
     * @var ItemTypeResolver
     */
    protected $resolver;

    public function __construct(ItemTypeResolver $itemTypeResolver)
    {
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'enhavo\ContentBundle\Entity\Item'
        ));
    }

    public function getName()
    {
        return 'enhavo_content_item';
    }
} 