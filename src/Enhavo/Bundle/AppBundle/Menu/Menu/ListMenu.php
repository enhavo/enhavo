<?php
/**
 * ListMenuBuilder.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenu;
use Enhavo\Bundle\AppBundle\Menu\MenuManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ListMenu extends AbstractMenu
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var MenuManager
     */
    private $menuManager;

    public function __construct(TranslatorInterface $translator, MenuManager $menuManager)
    {
        $this->translator = $translator;
        $this->menuManager = $menuManager;
    }

    public function createViewData(array $options)
    {
        $items = $this->menuManager->getMenuItemsByConfiguration($options['children']);

        $active = false;
        foreach($items as $menu) {
            if($menu->isActive()) {
                $active = true;
                break;
            }
        }

        $children = [];
        foreach($items as $menu) {
            $children[] = $menu->createViewData();
        }

        $data = [
            'label' => $this->translator->trans($options['label'], [], $options['translation_domain']),
            'icon' => $options['icon'],
            'component' => $options['component'],
            'class' => $options['class'],
            'active' => $active,
            'items' => $children
        ];

        $parentData = parent::createViewData($options);
        $data = array_merge($parentData, $data);
        return $data;
    }
    
    public function isHidden(array $options)
    {
        $isHidden = parent::isHidden($options);
        if(!$isHidden) {
            $items = $this->menuManager->getMenuItemsByConfiguration($options['children']);
            return count($items) == 0;
        }
        return $isHidden;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => 'menu-list',
            'translation_domain' => null,
            'icon' => null,
            'children' => [],
            'class' => null,
        ]);

        $resolver->setRequired([
            'label',
        ]);
    }

    public function getType()
    {
        return 'list';
    }
}
