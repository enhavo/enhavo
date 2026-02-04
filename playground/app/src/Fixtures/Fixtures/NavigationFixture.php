<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixtures\Fixtures;

use App\Fixtures\AbstractFixture;
use Enhavo\Bundle\CommentBundle\Exception\NotFoundException;
use Enhavo\Bundle\NavigationBundle\Factory\NodeFactory;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Resolver\NodeResolver;
use Enhavo\Bundle\PageBundle\Entity\Page;
use Symfony\Component\PropertyAccess\PropertyAccess;

class NavigationFixture extends AbstractFixture
{
    private $propertyAccessor;

    public function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function create($args)
    {
        $navigation = $this->container->get('enhavo_navigation.factory.navigation')->createNew();
        $navigation->setName($args['name']);
        $navigation->setCode($args['code']);

        $this->addNodes($navigation, $args['content']);

        $this->translate($navigation);

        return $navigation;
    }

    public function addNodes($resource, $content)
    {
        foreach ($content as $item) {
            $node = null;
            if (isset($item['content'])) {
                $node = $this->createNode(['type' => $item['type']]);
                $this->addNodes($node, $item['content']);
            } else {
                $node = $this->createNode($item);
            }

            if ($node) {
                if (method_exists($resource, 'addNode')) {
                    $resource->addNode($node);
                } elseif (method_exists($resource, 'addChild')) {
                    $resource->addChild($node);
                }
            }
        }
    }

    /**
     * @return NodeInterface|null
     */
    public function createNode(array $item)
    {
        $type = $item['type'];
        unset($item['type']);

        /** @var NodeResolver */
        $resolver = $this->container->get('enhavo_navigation.resolver.node_resolver');
        $node = null;
        try {
            /** @var NodeFactory $factory */
            $factory = $resolver->resolveFactory($type);
            $node = $factory->createNew();
        } catch (\Exception $ex) {
            print_r($ex->getMessage());
            echo PHP_EOL;
            exit;
        }

        if ($node) {
            if ('page' == $type && isset($item['link'])) {
                $page = $this->getPage($item['link']);
                if (!$page) {
                    throw new NotFoundException('Page with name"'.$item['link'].'" was not found"');
                }
                $node->setContent($page);
            }
            $config = ['target' => 'self'];
            if (isset($item['target'])) {
                $config['target'] = $item['target'];
            }
            $node->setConfiguration($config);

            $accessor = $this->propertyAccessor;
            foreach ($item as $k => $v) {
                if ($accessor->isWritable($node, $k)) {
                    $accessor->setValue($node, $k, $v);
                }

                $content = $node->getContent();
                if ($accessor->isWritable($content, $k)) {
                    $accessor->setValue($content, $k, $v);
                }
            }
        }

        return $node;
    }

    /**
     * @return Page|object|null
     */
    public function getPage($slug)
    {
        $page = $this->manager->getRepository('EnhavoPageBundle:Page')->findOneBy([
            'slug' => $slug,
        ]);

        return $page;
    }

    public function getName()
    {
        return 'Navigation';
    }

    public function getOrder()
    {
        return 30;
    }
}
