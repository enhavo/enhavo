<?php
/**
 * BaseConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\BlockBundle\Block\BlockTypeInterface;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseBlockType extends AbstractType implements BlockTypeInterface
{
    public function createViewData(BlockInterface $block, $resource, array $options)
    {

    }

    public function finishViewData(BlockInterface $block, array $viewData, $resource, array $options)
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

    public function getGroups(array $options)
    {
        return $options['groups'];
    }

    public function getLabel(array $options)
    {
        return $options['label'];
    }

    public function getTranslationDomain(array $options)
    {
        return $options['translation_domain'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'groups' => ['default'],
            'translation_domain' => null
        ]);

        $resolver->setRequired([
            'factory',
            'model',
            'template',
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
