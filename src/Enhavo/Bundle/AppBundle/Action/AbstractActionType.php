<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:15
 */

namespace Enhavo\Bundle\AppBundle\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractActionType implements ActionTypeInterface
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
            'icon' => null,
            'translation_domain' => null,
            'label' => null,
            'permission' => null,
            'hidden' => false
        ]);

        $resolver->setRequired([
            'component'
        ]);
    }

    public function createViewData(array $options, $resource = null)
    {
        $data = [
            'component' => $options['component'],
            'icon' => $options['icon'],
            'label' => $this->getLabel($options),
        ];

        return $data;
    }

    protected function getLabel(array $options)
    {
        return $this->translator->trans($options['label'], [], $options['translation_domain']);
    }
}