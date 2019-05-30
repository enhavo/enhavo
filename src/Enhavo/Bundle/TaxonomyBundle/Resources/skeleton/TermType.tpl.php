<?= "<?php\n"; ?>

namespace <?= $namespace; ?>;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Enhavo\Bundle\TaxonomyBundle\Form\Type\TermTreeChoiceType;

class <?= $class_name; ?> extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'label' => 'form.label.name',
            'translation_domain' => 'EnhavoAppBundle',
        ));

<?php if($hierarchy): ?>
        $builder->add('parent', TermTreeChoiceType::class, array(
            'class' => Term::class,
            'label' => 'form.label.parent',
            'translation_domain' => 'EnhavoAppBundle',
            'taxonomy' => '<?= $name; ?>',
            'placeholder' => '---',
        ));
<?php endif; ?>
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
