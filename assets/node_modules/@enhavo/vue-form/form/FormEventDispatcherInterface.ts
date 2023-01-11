import {EventInterface} from '@enhavo/vue-form/event/EventInterface';
import {Listener} from "@enhavo/vue-form/form/Listener";

export interface FormEventDispatcherInterface
{
    dispatchEvent(event: EventInterface, eventName: string): void;
    addListener(eventName: string | string[], callback: (event: EventInterface) => void): Listener;
    removeListener(listener: Listener): void;
}
