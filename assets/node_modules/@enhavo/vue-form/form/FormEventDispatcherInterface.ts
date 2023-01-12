import {EventInterface} from '@enhavo/vue-form/event/EventInterface';
import {Listener} from "@enhavo/vue-form/form/Listener";
import {Form} from "@enhavo/vue-form/model/Form";

export interface FormEventDispatcherInterface
{
    dispatchEvent(event: EventInterface, eventName: string): void;
    addListener(eventName: string | string[], callback: (event: EventInterface) => void, form: Form): Listener;
    on(eventName: string | string[], callback: (event: EventInterface) => void, form: Form): Listener;
    removeListener(listener: Listener): void;
}
