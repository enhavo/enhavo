<?= "<?php\n" ?>

namespace <?= $class->getNamespace() ?>;

use Symfony\Component\Form\AbstractType;
use <?= $entity->getNamespace() ?>\<?= $entity->getName() ?>;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
<?php foreach ($class->getUse() as $item): ?>
use <?= $item; ?>;
<?php endforeach; ?>

class <?= $class->getName() ?> extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Insert form fields
        $builder
<?php foreach ($definition->getFields() as $field): ?>
            ->add('<?= $field->getName() ?>', <?= $field->getClass() ?>::class, <?= $field->getOptionsString() ?>)

<?php endforeach; ?>
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => <?= $entity->getName() ?>::class
        ));
    }

    public function getBlockPrefix()
    {
        return '<?= $definition->getBlockPrefix() ?>';
    }
}
