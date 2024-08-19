<?php
/**
 * ListMenuBuilder.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Menu\MenuManager;
use Enhavo\Bundle\AppBundle\Menu\MenuTypeInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ListMenuType extends AbstractType implements MenuTypeInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly MenuManager $menuManager,
    )
    {
    }

    public function createViewData(array $options, Data $data): void
    {
        $items = $this->menuManager->getMenuItems($options['children']);

        $children = [];
        foreach ($items as $menu) {
            $children[] = $menu->createViewData();
        }

        $data->add([
            'label' => $this->translator->trans($options['label'], [], $options['translation_domain']),
            'icon' => $options['icon'],
            'component' => $options['component'],
            'model' => $options['model'],
            'class' => $options['class'],
            'items' => $children
        ]);
    }

    public function isEnabled(array $options): bool
    {
        $enabled = $options['enabled'];
        if ($enabled) {
            $items = $this->menuManager->getMenuItems($options['children']);
            return count($items) > 0;
        }
        return false;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'menu-list',
            'model' => 'ListMenuItem',
            'translation_domain' => null,
            'icon' => null,
            'children' => [],
            'class' => null,
            'enabled' => true,
            'role' => null,
        ]);
    }

    public function getPermission(array $options): mixed
    {
        return $options['role'];
    }

    public static function getName(): ?string
    {
        return 'list';
    }
}
