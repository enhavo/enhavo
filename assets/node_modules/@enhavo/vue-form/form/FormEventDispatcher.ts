import {EventInterface} from '@enhavo/vue-form/event/EventInterface';
import {Listener} from "@enhavo/vue-form/form/Listener";
import {Form} from "@enhavo/vue-form/model/Form";
import {FormEventDispatcherInterface} from "@enhavo/vue-form/form/FormEventDispatcherInterface";

export class FormEventDispatcher implements FormEventDispatcherInterface
{
    private listeners: Listener[] = [];

    dispatchEvent(event: EventInterface, eventName: string): void
    {
        for (let listener of this.listeners) {
            if (listener.eventNames.indexOf(eventName) >= 0) {
                if (listener.form && listener.form != event.form) {
                    continue;
                }
                listener.callback(event);
            }
        }
    }

    on(eventName: string|string[], callback: (event: EventInterface) => void, form: Form = null): Listener
    {
        return this.addListener(eventName, callback, form);
    }

    addListener(eventName: string|string[], callback: (event: EventInterface) => void, form: Form = null): Listener
    {
        let eventNames = Array.isArray(eventName) ? eventName : [eventName]
        let listener = new Listener(eventNames, callback, form);
        this.listeners.push(listener);
        return listener;
    }

    removeListener(listener: Listener): void
    {
        let index = this.listeners.indexOf(listener);
        if (index >= 0) {
            this.listeners.splice(index, 1);
        }
    }
}
