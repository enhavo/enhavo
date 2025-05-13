<?php echo "<?php\n"; ?>

namespace <?php echo $class->getNamespace(); ?>;

use Symfony\Component\Form\AbstractType;
use <?php echo $entity->getNamespace(); ?>\<?php echo $entity->getName(); ?>;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
<?php foreach ($class->getUse() as $item) { ?>
use <?php echo $item; ?>;
<?php } ?>

class <?php echo $class->getName(); ?> extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
<?php foreach ($form->getFields() as $field) { ?>
        $builder->add('<?php echo $field->getName(); ?>', <?php echo $field->getClass(); ?>::class, <?php echo $field->getOptionsString(); ?>);
<?php } ?>
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => <?php echo $entity->getName(); ?>::class
        ));
    }

    public function getBlockPrefix(): string
    {
        return '<?php echo $form->getBlockPrefix(); ?>';
    }
}
