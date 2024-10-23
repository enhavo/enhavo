<?php
/**
 * ThreeColumnConfiguration.php
 *
 * @since 08/08/18
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\BlockBundle\Model\Column\ThreeColumnBlock;
use Enhavo\Bundle\BlockBundle\Factory\ThreeColumnBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\ThreeColumnBlockType as ThreeColumnBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreeColumnBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'model' => ThreeColumnBlock::class,
            'form' => ThreeColumnBlockFormType::class,
            'factory' => ThreeColumnBlockFactory::class,
            'template' => 'theme/block/three-column.html.twig',
            'label' => 'three_column.label.three_column',
            'translation_domain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'layout']
        ]);
    }

    public static function getName(): ?string
    {
        return 'three_column';
    }
}
