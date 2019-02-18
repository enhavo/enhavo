<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-18
 * Time: 17:43
 */

namespace Enhavo\Bundle\AppBundle\Menu\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DropdownMenu extends AbstractMenu
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * BaseMenu constructor.
     *
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     */
    public function __construct(TranslatorInterface $translator, RouterInterface $router)
    {
        $this->translator = $translator;
        $this->router = $router;
    }

    public function createViewData(array $options)
    {
        $data = [
            'info' => $this->translator->trans($options['info'], [], $options['translation_domain']),
            'choices' => $options['choices']
        ];

        $parentData = parent::createViewData($options);
        $data = array_merge($parentData, $data);
        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'translation_domain' => null,
            'icon' => null,
            'class' => '',
            'component' => 'menu-dropdown',
            'info' => null,
        ]);

        $resolver->setRequired([
            'choices',
        ]);
    }

    public function getType()
    {
        return 'base';
    }
}