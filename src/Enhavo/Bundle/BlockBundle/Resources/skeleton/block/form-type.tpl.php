<?= "<?php\n" ?>

namespace <?= $form_namespace ?>;

use <?= $entity_namespace ?>\<?= $name ?>Block;
<?php if ($has_items): ?>
use Enhavo\Bundle\FormBundle\Form\Type\ListType;
<?php endif; ?>
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class <?= $name ?>BlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Insert form fields
<?php if ($has_items): ?>
        $builder
            ->add('items', ListType::class, [
                  'label' => 'Items',
                  'entry_type' => <?= $name ?>BlockItemType::class,
                  'sortable' => true,
            ])
        ;
<?php endif; ?>
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => <?= $name ?>Block::class
        ));
    }
}
