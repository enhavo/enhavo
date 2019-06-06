<?php
/**
 * ItemType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\DynamicItemType;
use Enhavo\Bundle\FormBundle\Form\Type\PositionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Enhavo\Bundle\GridBundle\Entity\Item;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('position', PositionType::class);
        $builder->add('name', HiddenType::class);
        $builder->add('itemType', $options['item_type_form'], $options['item_type_parameters']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Item::class,
            'item_type_form' => null,
            'item_type_parameters' => [],
            'item_property' => 'name',
        ));
    }

    public function getParent()
    {
        return DynamicItemType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_grid_item';
    }
} 