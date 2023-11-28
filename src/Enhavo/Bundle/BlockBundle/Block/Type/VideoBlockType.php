<?php

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Enhavo\Bundle\BlockBundle\Factory\VideoBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\VideoBlockType as VideoBlockFormType;
use Enhavo\Bundle\BlockBundle\Model\Block\VideoBlock;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoBlockType extends AbstractBlockType
{
    private ContainerInterface $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
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

    /**
     * @throws Exception
     */
    public function createViewData(BlockInterface $block, ViewData $viewData, $resource, array $options): void
    {
        if (!$this->container->has('Enhavo\Bundle\ContentBundle\Factory\VideoFactory')) {
            throw new Exception('You have to use the "enhavo/content-bundle" to use the video block');
        }

        $videoFactory = $this->container->get('Enhavo\Bundle\ContentBundle\Factory\VideoFactory');

        /** @var $block VideoBlock */
        try {
            $viewData->set('video', $videoFactory->create($block->getUrl()));
        } catch (Exception $e) {
            if (get_class($e) === 'Enhavo\Bundle\ContentBundle\Exception\VideoException') {
                $viewData->set('video', null);
            } else {
                throw $e;
            }
        }
    }

    public static function getName(): ?string
    {
        return 'video';
    }
}
