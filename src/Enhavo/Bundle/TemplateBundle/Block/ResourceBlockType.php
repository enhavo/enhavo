<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-25
 * Time: 17:24
 */

namespace Enhavo\Bundle\TemplateBundle\Block;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\TemplateBundle\Entity\ResourceBlock;
use Enhavo\Bundle\TemplateBundle\Factory\ResourceBlockFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Enhavo\Bundle\TemplateBundle\Form\Type\ResourceBlockType as ResourceBlockFormType;

class ResourceBlockType extends AbstractBlockType
{
    public function createViewData(BlockInterface $block, Data $data, $resource, array $options)
    {
        /** @var ResourceBlock $block */
        $data['template'] = $block->getTemplate();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'model' => ResourceBlock::class,
            'form' => ResourceBlockFormType::class,
            'factory' => ResourceBlockFactory::class,
            'template' => 'theme/block/resource.html.twig',
            'label' =>  'resource.label.resource',
            'translation_domain' => 'EnhavoTemplateBundle',
            'groups' => ['template']
        ]);
    }

    public static function getName(): ?string
    {
        return 'resource';
    }
}
