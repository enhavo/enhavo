<?php
/**
 * BaseConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\BlockBundle\Block\BlockTypeInterface;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseBlockType extends AbstractType implements BlockTypeInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly NormalizerInterface $normalizer,
    )
    {
    }

    public function createViewData(BlockInterface $block, Data $data, $resource, array $options)
    {
        $data['block'] = $this->normalizer->normalize($block, null, ['groups' => $options['serialization_groups']]);
    }

    public function finishViewData(BlockInterface $block, Data $data, $resource, array $options)
    {

    }

    public function getModel(array $options)
    {
        return $options['model'];
    }

    public function getForm(array $options)
    {
        return $options['form'];
    }

    public function getFactory(array $options)
    {
        return $options['factory'];
    }

    public function getTemplate(array $options)
    {
        return $options['template'];
    }

    public function getComponent(array $options)
    {
        return $options['component'];
    }

    public function getGroups(array $options)
    {
        return $options['groups'];
    }

    public function getLabel(array $options)
    {
        return $this->translator->trans($options['label'], [], $options['translation_domain']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'groups' => ['default'],
            'translation_domain' => null,
            'template' => null,
            'component' => null,
            'serialization_groups' => ['endpoint'],
        ]);

        $resolver->setRequired([
            'factory',
            'model',
            'form',
            'label',
        ]);
    }

    public static function getParentType(): ?string
    {
        return null;
    }

    public static function getName(): ?string
    {
        return 'base';
    }
}
