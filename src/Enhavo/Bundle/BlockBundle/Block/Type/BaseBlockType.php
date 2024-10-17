<?php
/**
 * BaseConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\BlockBundle\Block\BlockTypeInterface;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseBlockType extends AbstractType implements BlockTypeInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    )
    {
    }

    public function createViewData(BlockInterface $block, ViewData $viewData, $resource, array $options)
    {
        $viewData['block'] = $block;
    }

    public function finishViewData(BlockInterface $block, ViewData $viewData, $resource, array $options)
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
