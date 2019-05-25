<?= "<?php\n"; ?>

namespace <?= $namespace; ?>;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class <?= $class_name; ?> extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => '',
            'label' => '',
            'translation_domain' => '',
            'route' => '',
            'role' => ''
        ]);
    }

    public function getType()
    {
        return '<?= $name; ?>';
    }
}
