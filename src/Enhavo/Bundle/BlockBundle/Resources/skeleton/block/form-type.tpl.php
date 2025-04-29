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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
<?php foreach ($form->getFields() as $field): ?>
        $builder->add('<?= $field->getName() ?>', <?= $field->getClass() ?>::class, <?= $field->getOptionsString() ?>);
<?php endforeach; ?>
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => <?= $entity->getName() ?>::class
        ));
    }

    public function getBlockPrefix(): string
    {
        return '<?= $form->getBlockPrefix() ?>';
    }
}
