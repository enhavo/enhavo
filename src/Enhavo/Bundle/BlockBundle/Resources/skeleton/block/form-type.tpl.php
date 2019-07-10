<?= "<?php\n" ?>

namespace <?= $form_namespace ?>;

use <?= $entity_namespace ?>\<?= $name ?>Block;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class <?= $name ?>BlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Insert form fields
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => <?= $name ?>Block::class
        ));
    }
}
