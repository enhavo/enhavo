<?= "<?php\n"; ?>

namespace <?= $namespace; ?>;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class <?= $class_name; ?> extends AbstractMenuType
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
        return '<?= $name; ?>';
    }
}
