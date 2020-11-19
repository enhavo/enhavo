<?php
/**
 * @author blutze-media
 * @since 2020-11-04
 */

namespace Enhavo\Bundle\UserBundle\Controller;


use Symfony\Component\DependencyInjection\Container;

trait FlashMessagesTrait
{
    /** @var Container */
    protected $container;

    protected function getFlashMessages()
    {
        $flashBag = $this->container->get('session')->getFlashBag();
        $messages = [];
        $types = ['success', 'error', 'notice', 'warning'];
        foreach ($types as $type) {
            foreach ($flashBag->get($type) as $message) {
                $messages[] = [
                    'message' => is_array($message) ? $message['message'] : $message,
                    'type' => $type
                ];
            }
        }
        return $messages;
    }
}
