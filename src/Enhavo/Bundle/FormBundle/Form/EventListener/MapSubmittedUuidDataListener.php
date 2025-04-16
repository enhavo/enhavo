<?php

namespace Enhavo\Bundle\FormBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class MapSubmittedUuidDataListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly ?string $uuidProperty = null,
        private ?PropertyAccessor $propertyAccessor = null,
    )
    {
        $this->propertyAccessor = $propertyAccessor ?? new PropertyAccessor();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => ['preSubmit', 100],
        ];
    }

    public function preSubmit(FormEvent $event): void
    {
        if ($this->uuidProperty === null) {
            return;
        }

        $data = $event->getForm()->getData();
        $submittedData = $event->getData() ?? [];

        // Check for missing uuids
        foreach ($submittedData as $item) {
            if (!array_key_exists($this->uuidProperty, $item)) {
                $event->getForm()->addError(new FormError('Uuid missing'));
                break;
            } else if (empty($item[$this->uuidProperty])) {
                $event->getForm()->addError(new FormError('Uuid should not be blank'));
                break;
            }
        }

        // Create map for submitted key to array index
        $map = [];
        foreach ($submittedData as $name => $value) {
            $uuid = $this->propertyAccessor->getValue($value, '['.$this->uuidProperty.']');
            if ($uuid && $data) {
                foreach ($data as $index => $item) {
                    $itemUuid = $this->propertyAccessor->getValue($item, $this->uuidProperty);
                    if ($itemUuid !== null && $uuid === $itemUuid) {
                        $map[$name] = $index;
                        break;
                    }
                }

                if (!array_key_exists($name, $map)) {
                    $map[$name] = null;
                }
            } else {
                $map[$name] = null;
            }
        }

        if (count($map) === 0) {
            return;
        }

        // Fill empty index
        $max = -1;
        if ($data) {
            foreach ($data as $index => $value) {
                if ($index > $max) {
                    $max = $index;
                }
            }
        }
        $nextIndex = $max + 1;

        foreach ($map as $name => $index) {
            if ($index === null) {
                $index = $nextIndex;
                $nextIndex++;
            }
            $map[$name] = $index;
        }

        // Reassign indexes to submitted data
        $newSubmittedData = [];
        foreach ($submittedData as $name => $value) {
            $newSubmittedData[$map[$name]] = $value;
        }

        $event->setData($newSubmittedData);
    }
}
