<?php
/**
 * ListMenuBuilder.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\MenuManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ListMenuType extends AbstractMenuType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly MenuManager $menuManager,
    )
    {
    }

    public function createViewData(array $options, Data $data): void
    {
        $items = [];
        foreach ($this->menuManager->getMenuItems($options['items']) as $item) {
            $items[] = $item->createViewData();
        }

        $data->add([
            'label' => $this->translator->trans($options['label'], [], $options['translation_domain']),
            'icon' => $options['icon'],
            'items' => $items
        ]);
    }

    public function isEnabled(array $options): bool
    {
        $enabled = $options['enabled'];
        if ($enabled) {
            $items = $this->menuManager->getMenuItems($options['items']);
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
        ]);

        $resolver->setRequired([
            'label',
            'items',
        ]);
    }

    public static function getName(): ?string
    {
        return 'list';
    }
}
