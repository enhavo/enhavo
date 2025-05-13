<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class SortableArrayFormListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    public function preSubmit(FormEvent $event): void
    {
        $data = $event->getForm()->getData();
        $item = $event->getData();
        if ((is_array($data) || null === $data) && is_array($item)) {
            $itemKeys = array_keys($item);
            $copyValues = array_values($item);
            sort($itemKeys);
            $result = [];
            for ($i = 0; $i < count($itemKeys); ++$i) {
                $result[intval($itemKeys[$i])] = $copyValues[$i];
            }
            $event->setData($result);
        }
    }

    public function onSubmit(FormEvent $event): void
    {
        $data = $event->getForm()->getData();
        $items = $event->getData();
        if ((is_array($data) || null === $data) && is_array($items)) {
            $result = [];
            $i = 0;
            foreach ($items as $item) {
                $result[$i] = $item;
                ++$i;
            }
            $event->setData($result);
        }
    }
}
