<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-25
 * Time: 18:41
 */

namespace Enhavo\Bundle\CommentBundle\EventListener;

use Enhavo\Bundle\CommentBundle\Comment\PublishStrategyInterface;
use Enhavo\Bundle\CommentBundle\Event\PostPublishCommentEvent;
use Enhavo\Bundle\CommentBundle\Event\PrePublishCommentEvent;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvents;
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
            ResourceEvents::PRE_CREATE => 'preCreate',
            ResourceEvents::POST_CREATE => 'postCreate',
            ResourceEvents::PRE_UPDATE => 'onPreSave',
            ResourceEvents::POST_UPDATE => 'onPreSave',
        ];
    }

    public function preCreate(ResourceEvent $event)
    {
        if ($event->getSubject() instanceof CommentInterface) {
            $this->publishStrategy->preCreate($event->getSubject(), $this->options);
        }

        $this->onPreSave($event);
    }

    public function postCreate(ResourceEvent $event)
    {
        if ($event->getSubject() instanceof CommentInterface) {
            $this->publishStrategy->postCreate($event->getSubject(), $this->options);
        }

        $this->onPostSave($event);
    }

    public function preUpdate(ResourceEvent $event)
    {
        $this->onPreSave($event);
    }

    public function postUpdate(ResourceEvent $event)
    {
        $this->onPostSave($event);
    }

    public function onPreSave(ResourceEvent $event)
    {
        $comment = $event->getSubject();
        if($comment instanceof CommentInterface && $comment->isStateChanged() && $comment->getState() == CommentInterface::STATE_PUBLISH) {
            $this->eventDispatcher->dispatch(new PrePublishCommentEvent($comment));
        }
    }

    public function onPostSave(ResourceEvent $event)
    {
        $comment = $event->getSubject();
        if($comment instanceof CommentInterface && $comment->isStateChanged() && $comment->getState() == CommentInterface::STATE_PUBLISH) {
            $this->eventDispatcher->dispatch(new PostPublishCommentEvent($comment));
        }
    }
}
