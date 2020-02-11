<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-02-11
 * Time: 14:21
 */

namespace Enhavo\Bundle\AppBundle\Toolbar\Widget;

use Enhavo\Bundle\AppBundle\Toolbar\AbstractToolbarWidgetType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class QuickMenuToolbarWidgetType extends AbstractToolbarWidgetType
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(TranslatorInterface $translator, RouterInterface $router)
    {
        parent::__construct($translator);
        $this->router = $router;
    }

    public function createViewData($name, array $options)
    {
        $data = parent::createViewData($name, $options);
        $data['menu'] = $this->createMenu($options['menu']);
        $data['icon'] = $options['icon'];
        return $data;
    }

    private function createMenu(array $menu)
    {
        $data = [];
        foreach($menu as $config) {
            $data[] = [
                'target' => $this->getMenuTarget($config),
                'url' => $this->getMenuUrl($config),
                'label' => $this->getMenuLabel($config),
            ];
        }
        return $data;
    }

    private function getMenuUrl($config)
    {
        if (isset($config['url'])) {
            return $config['url'];
        } elseif(isset($config['route'])) {
            return $this->router->generate($config['route'], isset($config['route_parameters']) ? $config['route_parameters'] : []);
        }

        throw new \InvalidArgumentException(sprintf('A menu of quick menu need either url or route option'));
    }

    private function getMenuTarget($config)
    {
        if(!isset($config['target'])) {
            return '_view';
        }

        if(!in_array($config['target'], ['_view', '_blank', '_self'])) {
            throw new \InvalidArgumentException(sprintf('Target of menu from quick menu must be _view, _blank or _self, but "%s" given', $config['target']));
        }

        return $config['target'];
    }

    private function getMenuLabel($config)
    {
        if(!isset($config['label'])) {
            throw new \InvalidArgumentException(sprintf('A menu of quick need a label'));
        }

        return $this->translator->trans($config['label'], [], isset($config['translation_domain']) ? $config['translation_domain'] : null);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
           'icon' => null,
            'component' => 'quick-menu-widget'
        ]);

        $resolver->setRequired([
            'menu'
        ]);
    }

    public function getType()
    {
        return 'quick_menu';
    }
}
