<?php

namespace Enhavo\Bundle\DemoBundle\Fixtures\Fixtures;

use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Enhavo\Bundle\CommentBundle\Entity\Comment;
use Enhavo\Bundle\CommentBundle\Entity\Thread;
use Enhavo\Bundle\DemoBundle\Fixtures\AbstractFixture;
use Enhavo\Bundle\FormBundle\DynamicForm\ConfigurationInterface;
use Enhavo\Bundle\NavigationBundle\Entity\Link;
use Enhavo\Bundle\NavigationBundle\Entity\Navigation;
use Enhavo\Bundle\NavigationBundle\Item\ItemManager;
use Enhavo\Bundle\NavigationBundle\Resolver\NodeResolver;
use Enhavo\Bundle\TaxonomyBundle\Entity\Taxonomy;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class NavigationFixture extends AbstractFixture
{
    private $propertyAccessor;

    function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @inheritdoc
     */
    function create($args)
    {
        $navigation = $this->container->get('enhavo_navigation.factory.navigation')->createNew();
        $navigation->setName($args['name']);
        $navigation->setCode($args['code']);

        $this->addNodes($navigation, $args['content']);

        $this->translate($navigation);
        return $navigation;
    }

    function addNodes($resource, $content)
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

                } else if (method_exists($resource, 'addChild')) {
                    $resource->addChild($node);
                }
            }
        }
    }

    /**
     * @param array $item
     * @return \Enhavo\Bundle\NavigationBundle\Model\NodeInterface|null
     */
    function createNode(array $item)
    {
        $type = $item['type'];
        unset($item['type']);

        /** @var NodeResolver */
        $resolver = $this->container->get('enhavo_navigation.resolver.node_resolver');
        $node = null;
        try {
            $factory = $resolver->resolveFactory($type);
            $node = $factory->createNew();
        } catch (\Exception $ex) {}

        if ($node) {
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
     * @inheritdoc
     */
    function getName()
    {
        return 'Navigation';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 20;
    }
}
