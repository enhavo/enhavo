<?php
/**
 * OneColumnConfiguration.php
 *
 * @since 08/08/18
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\BlockBundle\Model\Column\OneColumnBlock;
use Enhavo\Bundle\BlockBundle\Factory\OneColumnBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\OneColumnBlockType as OneColumnBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OneColumnBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'model' => OneColumnBlock::class,
            'form' => OneColumnBlockFormType::class,
            'factory' => OneColumnBlockFactory::class,
            'template' => 'theme/block/one-column.html.twig',
            'label' => 'one_column.label.one_column',
            'translation_domain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'layout']
        ]);
    }

    public static function getName(): ?string
    {
        return 'one_column';
    }
}
