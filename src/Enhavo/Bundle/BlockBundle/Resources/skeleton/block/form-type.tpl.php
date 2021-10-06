<?= "<?php\n" ?>

namespace <?= $class->getNamespace() ?>;

<<<<<<< HEAD
use <?= $entity_namespace ?>\<?= $name ?>Block;
=======
>>>>>>> 941963ef3 (Block maker by yaml (#1355))
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
<<<<<<< HEAD
        // TODO: Insert form fields
=======
        $builder
<?php foreach ($form->getFields() as $field): ?>
            ->add('<?= $field->getName() ?>', <?= $field->getClass() ?>::class, <?= $field->getOptionsString() ?>)

<?php endforeach; ?>
        ;
>>>>>>> 941963ef3 (Block maker by yaml (#1355))
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => <?= $entity->getName() ?>::class
        ));
    }

    public function getBlockPrefix()
    {
        return '<?= $form->getBlockPrefix() ?>';
    }
}
