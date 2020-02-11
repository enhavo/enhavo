<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-02-11
 * Time: 02:15
 */

namespace Enhavo\Bundle\AppBundle\Toolbar;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractToolbarWidgetType implements ToolbarWidgetTypeInterface
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function isHidden(array $options)
    {
        return $options['hidden'];
    }

    public function getPermission(array $options)
    {
        return $options['permission'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => null,
            'permission' => null,
            'hidden' => false
        ]);

        $resolver->setRequired([
            'component'
        ]);
    }

    public function createViewData(array $options)
    {
        $data = [
            'component' => $options['component'],
            'icon' => $options['icon']
        ];

        return $data;
    }
}
