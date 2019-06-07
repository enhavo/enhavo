<?= "<?php\n" ?>

namespace <?= $namespace ?>;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class <?= $item_name ?>Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Insert form fields
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => '<?= $item_namespace ?>'
        ));
    }

    public function getName()
    {
        return '<?= $form_type_name ?>';
    }
}
