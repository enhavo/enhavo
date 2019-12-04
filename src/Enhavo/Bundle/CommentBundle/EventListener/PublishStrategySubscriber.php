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
use Enhavo\Bundle\CommentBundle\Event\PostPublishCommentEvent;
use Enhavo\Bundle\CommentBundle\Event\PreCreateCommentEvent;
use Enhavo\Bundle\CommentBundle\Event\PrePublishCommentEvent;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * PublishStrategySubscriber constructor.
     * @param PublishStrategyInterface $publishStrategy
     */
    public function __construct(PublishStrategyInterface $publishStrategy, array $options, EventDispatcherInterface $eventDispatcher)
    {
        $this->publishStrategy = $publishStrategy;
        $optionResolver = new OptionsResolver();
        $this->publishStrategy->configureOptions($optionResolver);
        $this->options = $optionResolver->resolve($options);
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getSubscribedEvents()
    {
        return [
            PreCreateCommentEvent::class => 'preCreate',
            PostCreateCommentEvent::class => 'postCreate',
            'enhavo_comment.comment.pre_update' => 'onPreSave',
            'enhavo_comment.comment.pre_create' => 'onPreSave',
            'enhavo_comment.comment.post_update' => 'onPostSave',
            'enhavo_comment.comment.post_create' => 'onPostSave',
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

    public function onPreSave(ResourceControllerEvent $event)
    {
        /** @var CommentInterface $comment */
        $comment = $event->getSubject();
        if($comment->isStateChanged() && $comment->getState() == CommentInterface::STATE_PUBLISH) {
            $this->eventDispatcher->dispatch(new PrePublishCommentEvent($comment));
        }
    }

    public function onPostSave(ResourceControllerEvent $event)
    {
        /** @var CommentInterface $comment */
        $comment = $event->getSubject();
        if($comment->isStateChanged() && $comment->getState() == CommentInterface::STATE_PUBLISH) {
            $this->eventDispatcher->dispatch(new PostPublishCommentEvent($comment));
        }
    }
}
