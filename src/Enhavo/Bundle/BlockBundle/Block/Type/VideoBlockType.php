<?php
/**
 * VideoConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\BlockBundle\Model\Block\VideoBlock;
use Enhavo\Bundle\BlockBundle\Factory\VideoBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\VideoBlockType as VideoBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoBlockType extends AbstractBlockType
{
    /** @var ContainerInterface */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'model' => VideoBlock::class,
            'form' => VideoBlockFormType::class,
            'factory' => VideoBlockFactory::class,
            'template' => 'theme/block/video.html.twig',
            'label' => 'video.label.video',
            'translation_domain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function createViewData(BlockInterface $block, ViewData $viewData, $resource, array $options)
    {
        if (!$this->container->has('Enhavo\Bundle\ContentBundle\Factory\VideoFactory')) {
            throw new \Exception('You have to use the "enhavo/content-bundle" to use the video block');
        }

        $videoFactory = $this->container->get('Enhavo\Bundle\ContentBundle\Factory\VideoFactory');

        /** @var $block VideoBlock */
        $viewData['video'] = $videoFactory->create($block->getUrl());
    }

    public static function getName(): ?string
    {
        return 'video';
    }
}
