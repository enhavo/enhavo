<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-25
 * Time: 18:41
 */

namespace Enhavo\Bundle\CommentBundle\EventListener;

use Enhavo\Bundle\CommentBundle\Comment\PublishStrategyInterface;
use Enhavo\Bundle\CommentBundle\Event\PostCreateCommentEvent;
use Enhavo\Bundle\CommentBundle\Event\PreCreateCommentEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublishStrategySubscriber  implements EventSubscriberInterface
{
    /**
     * @var PublishStrategyInterface
     */
    private $publishStrategy;

    /**
     * @var array
     */
    private $options;

    /**
     * PublishStrategySubscriber constructor.
     * @param PublishStrategyInterface $publishStrategy
     */
    public function __construct(PublishStrategyInterface $publishStrategy, array $options)
    {
        $this->publishStrategy = $publishStrategy;
        $optionResolver = new OptionsResolver();
        $this->publishStrategy->configureOptions($optionResolver);
        $this->options = $optionResolver->resolve($options);
    }

    public static function getSubscribedEvents()
    {
        return [
            PreCreateCommentEvent::class => 'preCreate',
            PostCreateCommentEvent::class => 'postCreate'
        ];
    }

    public function preCreate(PreCreateCommentEvent $event)
    {
        $this->publishStrategy->preCreate($event->getComment(), $this->options);
    }

    public function postCreate(PostCreateCommentEvent $event)
    {
        $this->publishStrategy->postCreate($event->getComment(), $this->options);
    }
}
