<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class <?php echo $class_name; ?> extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => '',
            'label' => '',
            'translation_domain' => '',
            'route' => '',
            'role' => ''
        ]);
    }

    public static function getName(): ?string
    {
        return '<?php echo $name; ?>';
    }
}
