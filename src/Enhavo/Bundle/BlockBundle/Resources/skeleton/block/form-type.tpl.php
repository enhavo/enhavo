<?= "<?php\n" ?>

namespace <?= $definition->getFormNamespace() ?>;

use <?= $definition->getEntityNamespace() ?>\<?= $definition->getName() ?>;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class <?= $class->getName() ?> extends <?= $class->getExtends() ?>
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Insert form fields
        $builder
<?php foreach ($definition->getFields() as $field): ?>
            ->add('<?= $field->getName() ?>', <?= $field->getClass() ?>, <?= $field->getOptionsString() ?>)

<?php endforeach; ?>
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => <?= $definition->getName() ?>::class
        ));
    }

    public function getBlockPrefix()
    {
        return '<?= $definition->getBlockPrefix() ?>';
    }
}
