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

class IconToolbarWidgetType extends AbstractToolbarWidgetType
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
        $data['label'] = $this->getLabel($options);
        $data['target'] = $this->getTarget($options);
        $data['icon'] = $options['icon'];
        $data['url'] = $this->getUrl($options);
        return $data;
    }

    private function getUrl($config)
    {
        if ($config['url'] !== null) {
            return $config['url'];
        } elseif($config['route'] !== null) {
            return $this->router->generate($config['route'], isset($config['route_parameters']) ? $config['route_parameters'] : []);
        }

        throw new \InvalidArgumentException(sprintf('Either url or route option must be defined'));
    }

    private function getTarget($config)
    {
        if(!in_array($config['target'], ['_view', '_blank', '_self'])) {
            throw new \InvalidArgumentException(sprintf('Target must be _view, _blank or _self, but "%s" given', $config['target']));
        }

        return $config['target'];
    }

    private function getLabel($config)
    {
        return $this->translator->trans($config['label'], [], $config['translation_domain'] ? $config['translation_domain'] : null);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'icon' => null,
            'component' => 'icon-widget',
            'route' => null,
            'url' => null,
            'route_parameters' => [],
            'translation_domain' => null,
            'target' => '_view',
            'label' => null
        ]);

        $resolver->setRequired([
            'icon'
        ]);
    }

    public function getType()
    {
        return 'icon';
    }
}
