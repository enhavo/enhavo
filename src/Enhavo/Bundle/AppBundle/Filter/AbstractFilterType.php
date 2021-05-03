<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 04.02.18
 * Time: 17:38
 */

namespace Enhavo\Bundle\AppBundle\Filter;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractFilterType extends AbstractType implements FilterTypeInterface
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * AbstractFilterType constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function createViewData($options, $name)
    {
        return [
            'label' => $this->getLabel($options),
            'type' => $this->getType(),
            'key' => $name,
            'component' => $options['component'],
            'active' => $options['initial_active'],
        ];
    }

    public function getPermission($options)
    {
        return $options['permission'];
    }

    public function isHidden($options)
    {
        return $options['hidden'];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'translation_domain' => null,
            'permission' => null,
            'hidden' => false,
            'initial_active' => false
        ]);

        $optionsResolver->setRequired([
            'label',
            'property',
            'component'
        ]);
    }

    protected function getLabel($options): string
    {
        return $this->translator->trans($options['label'], [], $options['translation_domain']);
    }
}
