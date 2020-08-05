<?php
/**
 * BlockquoteTextConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\BlockBundle\Model\Block\BlockquoteBlock;
use Enhavo\Bundle\BlockBundle\Factory\BlockquoteBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\BlockquoteBlockType as BlockquoteBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockquoteBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'model' => BlockquoteBlock::class,
            'form' => BlockquoteBlockFormType::class,
            'factory' =>  BlockquoteBlockFactory::class,
            'template' => 'theme/block/blockquote.html.twig',
            'label' =>  'blockquoteText.label.blockquoteText',
            'translation_domain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'content', 'simple']
        ]);
    }

    public static function getName(): ?string
    {
        return 'blockquote';
    }
}
