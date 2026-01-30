<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Enhavo\Bundle\BlockBundle\Factory\BlockFactory;
use Enhavo\Bundle\BlockBundle\Factory\NodeFactory;
use Enhavo\Bundle\BlockBundle\Model\Block\PictureBlock;
use Enhavo\Bundle\BlockBundle\Model\Block\TextBlock;
use Enhavo\Bundle\BlockBundle\Model\Block\TextPictureBlock;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Yaml\Yaml;

/**
 * @author gseidel
 */
abstract class AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->loadData($this->getName());
    }

    /**
     * Load the data from fixture file and insert it into the database
     *
     * @throws \Exception
     */
    public function loadData($name)
    {
        $file = sprintf('%s/../Resources/fixtures/%s.yaml', __DIR__, $name);

        if (!file_exists($file)) {
            throw new \Exception(sprintf('fixtures file "%s" not found for name "%s"', $file, $name));
        }

        $data = Yaml::parseFile($file);

        $items = [];
        foreach ($data as $args) {
            $item = $this->create($args);
            $this->manager->persist($item);
            $items[] = $item;
        }

        $this->manager->flush();
    }

    public function setContainer(?ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Save file and return its model.
     *
     * @throws \Exception
     *
     * @return FileInterface
     */
    protected function createImage($path)
    {
        $path = sprintf('%s/../Resources/images/%s', __DIR__, $path);
        $file = $this->container->get('enhavo_media.factory.file')->createFromPath($path);
        $this->container->get('enhavo_media.media.media_manager')->saveFile($file);

        return $file;
    }

    /**
     * Return DateTime object
     *
     * @return \DateTime
     */
    public function createDateTime($value)
    {
        $date = null;

        if (is_string($value)) {
            $date = new \DateTime($value);
        }

        if (is_int($value)) {
            $date = new \DateTime();
            $date->setTimestamp($value);
        }

        return $date;
    }

    /**
     * Return single Entity by Argument
     */
    abstract public function create($args);

    /**
     * Return the name of the fixture
     */
    abstract public function getName();

    abstract public function getOrder();

    /**
     * @return NodeInterface
     */
    protected function createContent($container)
    {
        /** @var NodeFactory $nodeFactory */
        $nodeFactory = $this->container->get('enhavo_block.node.factory');
        $containerEntity = $nodeFactory->createNew();

        $position = 0;
        foreach ($container as $fields) {
            $node = $nodeFactory->createNew();
            $type = $fields['type'];
            unset($fields['type']);

            $content = false;
            $setter = 'content';
            if (isset($fields['content'])) {
                $content = $fields['content'];
                unset($fields['content']);
            }
            if (isset($fields['setter'])) {
                $setter = $fields['setter'];
                unset($fields['setter']);
            }
            $block = $this->createBlockType($type, $fields);

            if (false !== $content) {
                $subNode = $this->createContent($content);
                if (false !== $setter) {
                    $accessor = PropertyAccess::createPropertyAccessor();
                    $accessor->setValue($block, $setter, $subNode);
                }
            }

            $node->setName($type);
            $node->setBlock($block);
            $node->setPosition($position++);
            $containerEntity->addChild($node);
        }
        $this->translate($containerEntity);

        return $containerEntity;
    }

    protected function createNodes(NodeInterface $parent, array $container)
    {
        /** @var NodeFactory $nodeFactory */
        $nodeFactory = $this->container->get('enhavo_block.node.factory');
        $nodes = [];
        $position = 0;
        foreach ($container as $fields) {
            $node = $nodeFactory->createNew();
            $type = $fields['type'];
            unset($fields['type']);

            $content = false;
            if (isset($fields['content'])) {
                $content = $fields['content'];
                unset($fields['content']);
            }
            $block = $this->createBlockType($type, $fields);

            $node->setName($type);
            $node->setBlock($block);
            $node->setPosition($position++);

            if (false !== $content) {
                $this->createNodes($node, $content);
            }

            $parent->addChild($node);
        }
    }

    /**
     * @return BlockInterface
     */
    protected function createBlockType($type, $fields)
    {
        /** @var BlockFactory $factory */
        $factory = $this->container->get('enhavo_block.block.factory');
        $itemType = $factory->createNew($type);

        $this->setFields($type, $itemType, $fields);
        $this->translate($itemType);

        return $itemType;
    }

    protected function setFields($type, $blockType, $fields)
    {
        switch ($type) {
            case 'text':
                /* @var $blockType TextBlock */
                $blockType->setText($fields['text']);
                $blockType->setTitle($fields['title']);
                break;
            case 'picture':
                /* @var $blockType PictureBlock */
                $blockType->setFile($this->createImage($fields['file']));
                $blockType->setTitle($fields['title']);
                $blockType->setCaption($fields['caption']);
                break;
            case 'text_picture':
                /* @var $blockType TextPictureBlock */
                $blockType->setFile($this->createImage($fields['file']));
                $blockType->setTitle($fields['title']);
                $blockType->setCaption($fields['caption']);
                $blockType->setFloat($fields['float']);
                $blockType->setTitle($fields['title']);
                $blockType->setText($fields['text']);
                break;
            case 'sidebar_column':
                $code = $fields['code'];
                $sidebar = $this->manager->getRepository('EnhavoSidebarBundle:Sidebar')->findOneBy([
                    'code' => $code,
                ]);
                $blockType->setSidebar($sidebar);
                break;
        }
    }

    protected function createRoute($url, $content)
    {
        $route = $this->container->get('enhavo_demo.factory.route')->createNew();
        $route->setName(preg_replace('/ */', '', strtolower($content->getTitle())));
        $route->setStaticPrefix($url);
        $route->setContent($content);
        $this->translate($route);

        return $route;
    }

    protected function translate($entity)
    {
        /* @var Translator $translator */
        //        $translator = $this->container->get('enhavo_translation.translator');
        //        $metadata = $translator->getMetadata($entity);
        //
        //        if($metadata === null) {
        //            return;
        //        }
        //
        //        $accessor = PropertyAccess::createPropertyAccessor();
        //        foreach($metadata->getProperties() as $property) {
        //            $formData = $accessor->getValue($entity, $property->getName());
        //            $translationData = $translator->normalizeToTranslationData($entity, $property->getName(), $formData);
        //            $translator->addTranslationData($entity, $property->getName(), $translationData);
        //            $normalizeData = $translator->normalizeToModelData($entity, $property->getName(), $formData);
        //            $accessor->setValue($entity, $property->getName(), $normalizeData);
        //        }
    }
}
